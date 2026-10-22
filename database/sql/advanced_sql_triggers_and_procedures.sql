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
    SET stock = CASE 
        WHEN stock >= NEW.quantity THEN stock - NEW.quantity 
        ELSE 0 
    END
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
    IF NEW.status = 'siap' AND NEW.ready_at IS NULL THEN
        SET NEW.ready_at = NOW();
    END IF;

    IF NEW.status = 'selesai' AND NEW.completed_at IS NULL THEN
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
-- Karakter : Transaksional (ACID) dengan START TRANSACTION, COMMIT, dan ROLLBACK Handler
-- Fungsi   : 1. Agregasi JOIN transaksi mentah harian per gerai tenant
--            2. Menyimpan/Upsert hasil ke tabel analitik `daily_tenant_summaries`
--            3. Menampilkan Result Set Ringkasan Eksekutif KPI Bandara Juanda
-- ----------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS sp_ringkasan_transaksi_harian$$
CREATE PROCEDURE sp_ringkasan_transaksi_harian(IN p_tanggal DATE)
BEGIN
    -- Error Handler Transaksional: Rollback otomatis jika ada error SQL
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    -- Buka transaksi terisolasi
    START TRANSACTION;

        -- 1. Idempotensi: Hapus data ringkasan lama untuk tanggal yang diproses jika ada
        DELETE FROM daily_tenant_summaries WHERE summary_date = p_tanggal;

        -- 2. Agregasi JOIN & Simpan hasil ke tabel ringkasan harian per tenant
        INSERT INTO daily_tenant_summaries (
            summary_date,
            tenant_id,
            total_orders,
            paid_orders,
            cancelled_orders,
            total_revenue,
            ready_orders,
            sla_compliant_orders,
            avg_sla_minutes,
            created_at,
            updated_at
        )
        SELECT 
            p_tanggal AS summary_date,
            t.id AS tenant_id,
            COUNT(o.id) AS total_orders,
            SUM(CASE WHEN o.is_paid = 1 THEN 1 ELSE 0 END) AS paid_orders,
            SUM(CASE WHEN o.status = 'dibatalkan' THEN 1 ELSE 0 END) AS cancelled_orders,
            COALESCE(SUM(CASE WHEN o.is_paid = 1 THEN o.total_amount ELSE 0 END), 0) AS total_revenue,
            SUM(CASE WHEN o.ready_at IS NOT NULL THEN 1 ELSE 0 END) AS ready_orders,
            SUM(CASE WHEN o.ready_at IS NOT NULL AND o.ready_at >= o.ordered_at AND TIMESTAMPDIFF(MINUTE, o.ordered_at, o.ready_at) <= 15 THEN 1 ELSE 0 END) AS sla_compliant_orders,
            ROUND(AVG(CASE WHEN o.ready_at IS NOT NULL AND o.ready_at >= o.ordered_at THEN TIMESTAMPDIFF(MINUTE, o.ordered_at, o.ready_at) END), 2) AS avg_sla_minutes,
            NOW(),
            NOW()
        FROM tenants t
        LEFT JOIN orders o ON t.id = o.tenant_id AND DATE(o.ordered_at) = p_tanggal
        GROUP BY t.id;

    -- Commit transaksi: seluruh agregasi harian tersimpan permanen
    COMMIT;

    -- 3. Menampilkan Result Set Ringkasan Eksekutif KPI Bandara Juanda
    SELECT 
        p_tanggal AS tanggal_rekap,
        COUNT(o.id) AS total_pesanan,
        SUM(CASE WHEN o.status = 'selesai' THEN 1 ELSE 0 END) AS pesanan_selesai,
        SUM(CASE WHEN o.status = 'dibatalkan' THEN 1 ELSE 0 END) AS pesanan_dibatalkan,
        ROUND(SUM(CASE WHEN o.status = 'dibatalkan' THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(o.id), 0), 1) AS cancel_rate_pct,
        COALESCE(SUM(CASE WHEN o.is_paid = 1 THEN o.total_amount ELSE 0 END), 0) AS total_omset_lunas,
        ROUND(COALESCE(AVG(CASE WHEN o.ready_at IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, o.ordered_at, o.ready_at) END), 0), 1) AS avg_sla_menit,
        (
            SELECT t2.name 
            FROM orders o2 
            JOIN tenants t2 ON o2.tenant_id = t2.id 
            WHERE DATE(o2.ordered_at) = p_tanggal AND o2.is_paid = 1 
            GROUP BY t2.id, t2.name 
            ORDER BY SUM(o2.total_amount) DESC 
            LIMIT 1
        ) AS top_tenant_hari_ini
    FROM orders o
    WHERE DATE(o.ordered_at) = p_tanggal;
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
