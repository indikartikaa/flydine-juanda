<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menerapkan Native Trigger dan Stored Procedure di level DBMS MySQL.
     */
    public function up(): void
    {
        // Hanya jalankan pada driver MySQL / MariaDB
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            
            // 1. Drop trigger lama jika ada (Idempotent)
            DB::unprepared('DROP TRIGGER IF EXISTS trg_after_order_item_insert_potong_stok;');
            DB::unprepared('DROP TRIGGER IF EXISTS trg_before_order_update_sla_guard;');
            DB::unprepared('DROP TRIGGER IF EXISTS trg_after_order_status_update;');
            DB::unprepared('DROP PROCEDURE IF EXISTS sp_ringkasan_transaksi_harian;');

            // -------------------------------------------------------------
            // TRIGGER 1: trg_after_order_item_insert_potong_stok
            // Tabel  : order_items
            // Event  : AFTER INSERT
            // Tujuan : Otomatis mengurangi kolom stok produk saat order dibuat
            // -------------------------------------------------------------
            DB::unprepared("
                CREATE TRIGGER trg_after_order_item_insert_potong_stok
                AFTER INSERT ON order_items
                FOR EACH ROW
                BEGIN
                    UPDATE products
                    SET stock = GREATEST(0, stock - NEW.quantity)
                    WHERE id = NEW.product_id;
                END;
            ");

            // -------------------------------------------------------------
            // TRIGGER 2: trg_before_order_update_sla_guard
            // Tabel  : orders
            // Event  : BEFORE UPDATE
            // Tujuan : Menjamin integritas SLA Bandara: otomatis mengisi ready_at 
            //          saat status menjadi 'siap' jika belum terisi, dan completed_at
            // -------------------------------------------------------------
            DB::unprepared("
                CREATE TRIGGER trg_before_order_update_sla_guard
                BEFORE UPDATE ON orders
                FOR EACH ROW
                BEGIN
                    -- Otomatis isi ready_at jika status diubah menjadi siap
                    IF NEW.status = 'siap' AND (NEW.ready_at IS NULL OR NEW.ready_at = '0000-00-00 00:00:00') THEN
                        SET NEW.ready_at = NOW();
                    END IF;

                    -- Otomatis isi completed_at jika status diubah menjadi selesai
                    IF NEW.status = 'selesai' AND (NEW.completed_at IS NULL OR NEW.completed_at = '0000-00-00 00:00:00') THEN
                        SET NEW.completed_at = NOW();
                    END IF;
                END;
            ");

            // -------------------------------------------------------------
            // TRIGGER 3: trg_after_order_status_update
            // Tabel  : orders
            // Event  : AFTER UPDATE
            // Tujuan : 1. Mencatat audit trail ke activity_logs jika status berubah
            //          2. Mengembalikan stok jika pesanan dibatalkan
            // -------------------------------------------------------------
            DB::unprepared("
                CREATE TRIGGER trg_after_order_status_update
                AFTER UPDATE ON orders
                FOR EACH ROW
                BEGIN
                    -- Audit Trail: Catat riwayat perubahan status pesanan
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

                    -- Auto-Restore Stok: Jika status berubah menjadi dibatalkan
                    IF OLD.status <> 'dibatalkan' AND NEW.status = 'dibatalkan' THEN
                        UPDATE products p
                        INNER JOIN order_items oi ON p.id = oi.product_id
                        SET p.stock = p.stock + oi.quantity
                        WHERE oi.order_id = NEW.id;
                    END IF;
                END;
            ");

            // -------------------------------------------------------------
            // STORED PROCEDURE: sp_ringkasan_transaksi_harian
            // Parameter : IN p_tanggal DATE
            // Tujuan    : Menghasilkan laporan analitik agregasi omset, SLA,
            //             dan performa tenant per hari langsung dari mesin MySQL
            // -------------------------------------------------------------
            DB::unprepared("
                CREATE PROCEDURE sp_ringkasan_transaksi_harian(IN p_tanggal DATE)
                BEGIN
                    -- 1. Result Set Utama: Ringkasan Eksekutif KPI Bandara Juanda
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

                    -- 2. Result Set Kedua: Rincian Kinerja per Mitra Tenant
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
                END;
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            DB::unprepared('DROP TRIGGER IF EXISTS trg_after_order_item_insert_potong_stok;');
            DB::unprepared('DROP TRIGGER IF EXISTS trg_before_order_update_sla_guard;');
            DB::unprepared('DROP TRIGGER IF EXISTS trg_after_order_status_update;');
            DB::unprepared('DROP PROCEDURE IF EXISTS sp_ringkasan_transaksi_harian;');
        }
    }
};
