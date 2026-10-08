-- ============================================================================
-- FLYDINE JUANDA - ADVANCED SQL: NATIVE TRIGGERS & STORED PROCEDURE
-- Database: MySQL / MariaDB (FlyDine Juanda F&B Airport Ordering System)
-- ============================================================================

DELIMITER $$

-- ----------------------------------------------------------------------------
-- 1. TRIGGER: trg_after_order_item_insert_potong_stok
-- Tabel   : order_items
-- Timing  : AFTER INSERT
-- Fungsi  : Otomatis memotong stok produk saat item pesanan baru dimasukkan.
--           Memastikan integritas inventori F&B di level DBMS.
-- ----------------------------------------------------------------------------
DROP TRIGGER IF EXISTS trg_after_order_item_insert_potong_stok$$
CREATE TRIGGER trg_after_order_item_insert_potong_stok
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    UPDATE products
    SET stock = GREATEST(0, stock - NEW.quantity)
    WHERE id = NEW.product_id;
END$$

-- ----------------------------------------------------------------------------
-- 2. TRIGGER: trg_before_order_update_sla_guard
-- Tabel   : orders
-- Timing  : BEFORE UPDATE
-- Fungsi  : Menjamin integritas metrik SLA Bandara Juanda:
--           - Otomatis mengisi kolom ready_at saat pesanan 'siap'
--           - Otomatis mengisi kolom completed_at saat pesanan 'selesai'
-- ----------------------------------------------------------------------------
DROP TRIGGER IF EXISTS trg_before_order_update_sla_guard$$
CREATE TRIGGER trg_before_order_update_sla_guard
BEFORE UPDATE ON orders
FOR EACH ROW
BEGIN
    IF NEW.status = 'siap' AND (NEW.ready_at IS NULL OR NEW.ready_at = '0000-00-00 00:00:00') THEN
        SET NEW.ready_at = NOW();
    END IF;

    IF NEW.status = 'selesai' AND (NEW.completed_at IS NULL OR NEW.completed_at = '0000-00-00 00:00:00') THEN
        SET NEW.completed_at = NOW();
    END IF;
END$$

-- ----------------------------------------------------------------------------
-- 3. TRIGGER: trg_after_order_status_update
-- Tabel   : orders
-- Timing  : AFTER UPDATE
-- Fungsi  : - Audit Trail: Mencatat riwayat pergantian status pesanan ke activity_logs
--           - Auto-Restore: Mengembalikan kuantitas stok jika pesanan dibatalkan
-- ----------------------------------------------------------------------------
DROP TRIGGER IF EXISTS trg_after_order_status_update$$
CREATE TRIGGER trg_after_order_status_update
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    -- Audit Logging perubahan status
    IF OLD.status <> NEW.status THEN
        INSERT INTO activity_logs (
            user_id,
            role,
            action,
            table_name,
            record_id,
            description,
            created_at,
            updated_at
        ) VALUES (
            NULL,
            'system_trigger',
            'UPDATE_STATUS',
            'orders',
            NEW.id,
            CONCAT('Status order #', NEW.order_code, ' diubah dari [', OLD.status, '] ke [', NEW.status, '] via MySQL Trigger'),
            NOW(),
            NOW()
        );
    END IF;

    -- Pengembalian stok otomatis jika dibatalkan
    IF OLD.status <> 'dibatalkan' AND NEW.status = 'dibatalkan' THEN
        UPDATE products p
        INNER JOIN order_items oi ON p.id = oi.product_id
        SET p.stock = p.stock + oi.quantity
        WHERE oi.order_id = NEW.id;
    END IF;
END$$

-- ----------------------------------------------------------------------------
-- 4. STORED PROCEDURE: sp_ringkasan_transaksi_harian
-- Parameter: IN p_tanggal DATE
-- Fungsi   : Menghasilkan 2 Result Set agregasi analitik harian:
--            1. Ringkasan Eksekutif KPI Bandara (Omset, SLA, Cancel Rate)
--            2. Breakdown Rincian Kinerja per Tenant F&B
-- ----------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS sp_ringkasan_transaksi_harian$$
CREATE PROCEDURE sp_ringkasan_transaksi_harian(IN p_tanggal DATE)
BEGIN
    -- Result Set 1: Ringkasan Metrik KPI Eksekutif Harian Bandara Juanda
    SELECT 
        p_tanggal AS tanggal_rekap,
        COUNT(o.id) AS total_pesanan,
        SUM(CASE WHEN o.status = 'selesai' THEN 1 ELSE 0 END) AS pesanan_selesai,
        SUM(CASE WHEN o.status = 'dibatalkan' THEN 1 ELSE 0 END) AS pesanan_dibatalkan,
        ROUND(SUM(CASE WHEN o.status = 'dibatalkan' THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(o.id), 0), 1) AS cancel_rate_pct,
        COALESCE(SUM(CASE WHEN o.is_paid = 1 THEN o.total_amount ELSE 0 END), 0) AS total_omset_lunas,
        ROUND(COALESCE(AVG(CASE WHEN o.ready_at IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, o.ordered_at, o.ready_at) END), 0), 1) AS avg_sla_menit,
        (
            SELECT t.name 
            FROM orders o2 
            JOIN tenants t ON o2.tenant_id = t.id 
            WHERE DATE(o2.ordered_at) = p_tanggal AND o2.is_paid = 1 
            GROUP BY t.id, t.name 
            ORDER BY SUM(o2.total_amount) DESC 
            LIMIT 1
        ) AS top_tenant_hari_ini
    FROM orders o
    WHERE DATE(o.ordered_at) = p_tanggal;

    -- Result Set 2: Breakdown Kinerja per Mitra Tenant
    SELECT 
        t.id AS tenant_id,
        t.name AS nama_tenant,
        COUNT(o.id) AS total_order,
        SUM(CASE WHEN o.status = 'selesai' THEN 1 ELSE 0 END) AS order_selesai,
        SUM(CASE WHEN o.status = 'dibatalkan' THEN 1 ELSE 0 END) AS order_batal,
        COALESCE(SUM(CASE WHEN o.is_paid = 1 THEN o.total_amount ELSE 0 END), 0) AS total_omset,
        ROUND(COALESCE(AVG(CASE WHEN o.ready_at IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, o.ordered_at, o.ready_at) END), 0), 1) AS avg_sla_menit
    FROM tenants t
    LEFT JOIN orders o ON t.id = o.tenant_id AND DATE(o.ordered_at) = p_tanggal
    GROUP BY t.id, t.name
    ORDER BY total_omset DESC, total_order DESC;
END$$

DELIMITER ;

-- ============================================================================
-- CARA PENGUJIAN OLEH DOSEN / PENGUJI:
-- 1. Cek daftar Trigger aktif:
--    SHOW TRIGGERS;
--
-- 2. Cek daftar Stored Procedure aktif:
--    SHOW PROCEDURE STATUS WHERE Db = DATABASE();
--
-- 3. Eksekusi Stored Procedure Rekap Harian:
--    CALL sp_ringkasan_transaksi_harian('2026-10-07');
-- ============================================================================
