-- ============================================================================
-- FLYDINE JUANDA - DBMS SECURITY: RBAC (ROLE-BASED ACCESS CONTROL) & ENCRYPTION
-- Database : MySQL / MariaDB (FlyDine Juanda F&B Ordering System)
-- Topik    : Kontrol Akses Pengguna DBMS (User & GRANT) serta Enkripsi Kredensial
-- Standar  : Principle of Least Privilege (PoLP) & Defense-in-Depth
-- ============================================================================

-- ============================================================================
-- BAGIAN 1: PENERAPAN RBAC LEVEL DBMS (CREATE USER & GRANT MYSQL)
-- ============================================================================
-- Konsep:
-- Memisahkan hak akses di tingkat mesin MySQL agar aplikasi tidak terhubung 
-- menggunakan akun 'root' (Superuser). Jika aplikasi web mengalami kebocoran 
-- atau serangan SQL Injection, peretas tidak memiliki wewenang untuk DROP TABLE, 
-- ALTER DATABASE, atau mengambil alih server DBMS.
-- ----------------------------------------------------------------------------

-- 1. ROLE 1: Web Application Service Account (Operasional Harian Kasir/Tenant/Customer)
-- Hak Akses: SELECT, INSERT, UPDATE, DELETE pada seluruh tabel operasional.
-- Larangan  : DILARANG DROP TABLE, TRUNCATE, ALTER TABLE, atau GRANT OPTION.
CREATE USER IF NOT EXISTS 'flydine_app'@'localhost' IDENTIFIED BY 'FlyDine2026!App';
GRANT SELECT, INSERT, UPDATE, DELETE ON flydine_juanda.* TO 'flydine_app'@'localhost';

-- 2. ROLE 2: Tim Analis Data & Business Intelligence (Reporting Read-Only)
-- Hak Akses: Hanya boleh membaca (SELECT) tabel agregasi analitik & tenant,
--            serta hak menjalankan (EXECUTE) Stored Procedure analitik harian.
-- Larangan  : DILARANG KERAS INSERT, UPDATE, DELETE (Mencegah manipulasi angka omset/keuangan).
CREATE USER IF NOT EXISTS 'flydine_bi_analyst'@'localhost' IDENTIFIED BY 'FlyDine2026!Analyst';
GRANT SELECT ON flydine_juanda.daily_tenant_summaries TO 'flydine_bi_analyst'@'localhost';
GRANT SELECT ON flydine_juanda.tenants TO 'flydine_bi_analyst'@'localhost';
GRANT SELECT ON flydine_juanda.orders TO 'flydine_bi_analyst'@'localhost';
GRANT EXECUTE ON PROCEDURE flydine_juanda.sp_ringkasan_transaksi_harian TO 'flydine_bi_analyst'@'localhost';

-- 3. ROLE 3: Auditor Kepatuhan & Keamanan (Read-Only Audit Trail)
-- Hak Akses: Hanya boleh membaca tabel activity_logs (log audit perubahan status).
-- Larangan  : DILARANG mengubah atau menghapus riwayat audit log.
CREATE USER IF NOT EXISTS 'flydine_auditor'@'localhost' IDENTIFIED BY 'FlyDine2026!Audit';
GRANT SELECT ON flydine_juanda.activity_logs TO 'flydine_auditor'@'localhost';

-- Terapkan hak akses ke memori MySQL
FLUSH PRIVILEGES;


-- ============================================================================
-- BAGIAN 2: ENKRIPSI KREDENSIAL & DATA SENSITIF
-- ============================================================================
-- Konsep:
-- 1. Kredensial Akun (Kata Sandi) -> HASHING KRIPTOGRAFIS SATU ARAH (One-Way)
--    Menggunakan Bcrypt (Blowfish) 60 karakter via Laravel Hash::make() & cast 'hashed'.
--    Sifat: Irreversible (Tidak bisa didekripsi, bahkan oleh DBA/Admin database).
--
-- 2. Data Sensitif Dua Arah (Two-Way Encryption)
--    Data yang masih perlu dibaca kembali oleh kasir/penumpang (misal: No HP, NIK, Token API)
--    dienkripsi menggunakan algoritma Advanced Encryption Standard (AES-256-CBC).
-- ----------------------------------------------------------------------------

-- CONTOH 1: Verifikasi Hashing Kredensial Pengguna di Tabel Users
-- Kolom password tersimpan dalam bentuk string hash (diawali $2y$12$...):
SELECT id, name, email, role, password 
FROM users 
LIMIT 3;

-- CONTOH 2: Enkripsi Dua Arah Native di Level DBMS MySQL (AES_ENCRYPT / AES_DECRYPT)
-- Data Sensitif (misal nomor telepon penumpang) dienkripsi dengan kunci rahasia:
SET @kunci_rahasia = 'JuandaAirportSecureKey2026!';

-- Proses Enkripsi Native MySQL (Simpan sebagai Ciphertext):
SELECT 
    '081234567890' AS nomor_telepon_asli,
    TO_BASE64(AES_ENCRYPT('081234567890', @kunci_rahasia)) AS ciphertext_terenkripsi,
    CAST(AES_DECRYPT(FROM_BASE64(TO_BASE64(AES_ENCRYPT('081234567890', @kunci_rahasia))), @kunci_rahasia) AS CHAR) AS plaintext_setelah_didekripsi;


-- ============================================================================
-- CARA PENGUJIAN OLEH DOSEN / PENGUJI:
-- ============================================================================
-- 1. Cek hak akses User Aplikasi (flydine_app):
--    SHOW GRANTS FOR 'flydine_app'@'localhost';
--
-- 2. Cek hak akses User Analis (flydine_bi_analyst):
--    SHOW GRANTS FOR 'flydine_bi_analyst'@'localhost';
--
-- 3. Cek hak akses User Auditor (flydine_auditor):
--    SHOW GRANTS FOR 'flydine_auditor'@'localhost';
--
-- 4. Uji simulasi keamanan (Mencoba aksi terlarang dengan user flydine_bi_analyst):
--    -- Jalankan saat login sebagai flydine_bi_analyst:
--    -- UPDATE orders SET total_amount = 0 WHERE id = 1; 
--    -- Hasil DBMS: ERROR 1142 (42000): UPDATE command denied to user 'flydine_bi_analyst'
-- ============================================================================
