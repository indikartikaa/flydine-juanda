-- ============================================================================
-- FLYDINE JUANDA - QUERY PERFORMANCE TUNING & EXPLAIN PLAN REPORT
-- Database : MySQL / MariaDB (FlyDine Juanda F&B Ordering System)
-- Topik    : Analisis Jalur Eksekusi (Explain Plan) & Pembuatan Indeks Komposit
-- ============================================================================

-- ============================================================================
-- SKENARIO 1: DRILL-DOWN TRANSAKSI GERAI TENANT
-- ============================================================================
-- Kasus Bisnis:
-- Admin operasional melakukan penelusuran (drill-down) ke gerai tertentu (misal: tenant_id = 1)
-- untuk melihat seluruh transaksi terbaru tanpa membatasi status pesanan.
-- Kueri di backend (AnalyticsController):

EXPLAIN SELECT id, tenant_id, ordered_at 
FROM orders 
WHERE tenant_id = 1 
ORDER BY ordered_at DESC;

-- ----------------------------------------------------------------------------
-- HASIL EVALUASI OPTIMASI (BEFORE vs AFTER):
-- ----------------------------------------------------------------------------
-- 1. SEBELUM INDEKS KOMPOSIT BARU (Hanya ada index: tenant_id, status, ordered_at):
--    - type         : ref
--    - key          : orders_tenant_id_status_ordered_at_index
--    - Extra        : Using filesort
--    * Analisis Masalah: Kolom 'status' berada di tengah index B-Tree, memutus aturan
--      Leftmost Prefix. Akibatnya MySQL terpaksa menyortir manual di RAM (Using filesort).
--
-- 2. SETELAH PENAMBAHAN INDEKS KOMPOSIT: idx_orders_tenant_ordered (tenant_id, ordered_at):
--    - type         : ref
--    - key          : idx_orders_tenant_ordered
--    - Extra        : Backward index scan; Using index
--    * Peningkatan Performa:
--      a. 'Using filesort' 100% LENYAP karena data di B-Tree sudah otomatis terurut.
--      b. Terjadi 'Covering Index' (Using index): data dibaca langsung dari memori RAM index
--         tanpa perlu melakukan pembacaan blok data tabel di hard disk.
-- ----------------------------------------------------------------------------


-- ============================================================================
-- SKENARIO 2: FILTERING STATUS TRANSAKSI KONSOLIDASI (SEMUA GERAI)
-- ============================================================================
-- Kasus Bisnis:
-- Admin memantau transaksi pesanan berdasarkan status tertentu (misal: 'selesai' atau 'dibatalkan')
-- pada seluruh gerai bandara yang diurutkan dari waktu paling baru.

EXPLAIN SELECT id, order_code, tenant_id, total_amount, status, ordered_at 
FROM orders 
WHERE status = 'selesai' 
ORDER BY ordered_at DESC;

-- ----------------------------------------------------------------------------
-- HASIL EVALUASI OPTIMASI (BEFORE vs AFTER):
-- ----------------------------------------------------------------------------
-- 1. SEBELUM INDEKS KOMPOSIT BARU:
--    - type         : ALL (Full Table Scan!)
--    - key          : NULL (Tidak ada index yang cocok karena index lama diawali tenant_id)
--    - rows         : Membaca SELURUH baris di tabel orders (misal 200+ baris pada demo)
--    - Extra        : Using where; Using filesort
--    * Analisis Masalah: Sangat berbahaya di sistem produksi saat volume mencapai jutaan baris,
--      menyebabkan CPU 100% dan latency request melonjak drastis.
--
-- 2. SETELAH PENAMBAHAN INDEKS KOMPOSIT: idx_orders_status_ordered (status, ordered_at):
--    - type         : ref (Langsung melompat ke bucket index 'selesai')
--    - key          : idx_orders_status_ordered
--    - filtered     : 100% (Seluruh data yang dibaca relevan)
--    - Extra        : Backward index scan
--    * Peningkatan Performa:
--      a. Menghilangkan Full Table Scan (ALL -> ref).
--      b. 'Using filesort' tereliminasi sepenuhnya.
-- ----------------------------------------------------------------------------


-- ============================================================================
-- CARA PENGUJIAN OLEH DOSEN / PENGUJI:
-- ============================================================================
-- 1. Cek daftar index aktif pada tabel orders:
--    SHOW INDEX FROM orders;
--
-- 2. Jalankan Explain Plan Skenario 1 (Tenant Drilldown):
--    EXPLAIN SELECT id, tenant_id, ordered_at FROM orders WHERE tenant_id = 1 ORDER BY ordered_at DESC;
--
-- 3. Jalankan Explain Plan Skenario 2 (Status Filter):
--    EXPLAIN SELECT id, order_code, tenant_id, total_amount, status, ordered_at FROM orders WHERE status = 'selesai' ORDER BY ordered_at DESC;
-- ============================================================================
