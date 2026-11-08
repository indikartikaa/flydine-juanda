# DOKUMEN KESIAPAN RILIS PRODUK MINIMUM (RELEASE READINESS MVP)
## Sistem FlyDine Juanda International Airport (SUB)
**Versi Produk:** MVP v1.0.0  
**Tanggal Pengesahan:** 2026-11-04  
**Status Kelayakan:** DECLARED READY FOR PRODUCTION LAUNCH (MVP APPROVED)  

---

## 1. TUJUAN & PERNYATAAN KESIAPAN RILIS

Dokumen ini menyatakan secara resmi bahwa platform **FlyDine Bandara Internasional Juanda** telah berhasil melewati seluruh tahapan *Software Testing Life Cycle* (STLC), memenuhi kriteria kelulusan uji (*exit criteria*), serta siap untuk dideploy dan diluncurkan ke lingkungan produksi sebagai **Minimum Viable Product (MVP)**.

---

## 2. RUANG LINGKUP FITUR MVP YANG DIRILIS

| Modul Utama | Cakupan Fitur MVP | Status Validasi |
| :--- | :--- | :---: |
| **Katalog & Terminal** | Direktori restoran dan menu per Terminal (Terminal 1 & Terminal 2) Bandara Juanda, pencarian instan, dan filter kategori. | **SIAP** |
| **Pemesanan & Keranjang** | Validasi kuantitas stok atomik, varian kustom menu, catatan penyiapan khusus, dan perlindungan *anti-price tampering*. | **SIAP** |
| **Kepatuhan Bandara** | Penegakan batas keamanan zonasi (*Airside* vs *Landside*), pencegahan pengantaran lintas terminal, dan SLA waktu penerbangan. | **SIAP** |
| **Auto-Cancel Engine** | Timer hitung mundur un-paid 15 menit tersinkronisasi jadwal boarding, *Passive Cancel*, dan *Active Cron Scheduler*. | **SIAP** |
| **Simulasi Pembayaran** | Pembayaran QRIS dinamis dan Virtual Account Bank mitra (Mandiri, BCA, BRI, BNI). | **SIAP** |
| **Pelacakan Live & Trigger** | Halaman tracking asinkron AJAX, notifikasi status live, dan tombol pemicu *"Saya Menuju Tenant"* (`heading_to_tenant_at`). | **SIAP** |
| **Portal Kasir Tenant** | Antrean pesanan *real-time*, transisi *Strict Forward State Machine*, dan pengaturan jam operasional tenant. | **SIAP** |
| **Proteksi Anti-Fraud** | Batas maksimal 5 pesanan aktif belum bayar dan pemblokiran otomatis bagi akun terdeteksi spam pembatalan. | **SIAP** |

---

## 3. CHECKLIST PRA-PELUNCURAN PRODUKSI (PRODUCTION DEPLOYMENT CHECKLIST)

- [x] **Basis Data & Skema:** Seluruh 31 file migrasi telah tereksekusi penuh (`Batch Ran`) di lingkungan MySQL 8.0.
- [x] **Database Trigger & Stored Procedures:** Trigger `trg_after_order_item_insert_potong_stok`, `trg_before_order_update_sla_guard`, dan `trg_after_order_status_update` aktif di MySQL.
- [x] **Seeder Data Awal:** Data master tenant Juanda (Terminal 1 & 2), kategori produk, lokasi antar porter, dan akun staf kasir telah terisi.
- [x] **Optimasi Cache Laravel:** Konfigurasi `php artisan config:cache`, `php artisan route:cache`, dan `php artisan view:cache` siap dieksekusi.
- [x] **Scheduler Background Job:** Perintah `php artisan schedule:work` / Cron terkonfigurasi untuk mengeksekusi `orders:cancel-expired` setiap 60 detik.
- [x] **Uji Regresi Otomatis:** 136 pengujian otomatis berstatus **PASS** dengan 920 assertions terverifikasi tanpa eror.
- [x] **Resolusi Defect:** 100% bug (termasuk 3 Blocker dan 4 Critical) telah terselesaikan dan lolos uji ulang (*Re-tested*).

---

## 4. LEMBAR PERSETUJUAN PENERIMAAN PENGGUNA (UAT SIGN-OFF FORM)

Berdasarkan hasil uji coba penerimaan pengguna (*User Acceptance Testing*) yang melibatkan perwakilan calon penumpang dan mitra tenant Bandara Internasional Juanda:

| Pihak Penguji / Pemangku Kepentingan | Perwakilan / Nama | Jabatan / Peran | Status Persetujuan | Tanda Tangan Digital |
| :--- | :--- | :--- | :---: | :---: |
| **Otoritas Penjamin Mutu (QA Lead)** | Tim QA FlyDine | Lead Software Quality Assurance | **APPROVED** | *(Verified - Digital QA)* |
| **Pengembang Utama (Dev Lead)** | Tim Developer | Lead System Engineer | **APPROVED** | *(Verified - Git Commit)* |
| **Perwakilan Mitra Tenant Bandara** | Mitra Tenant T1/T2 | Perwakilan Kasir Restoran | **ACCEPTED** | *(Signed UAT Sheet)* |
| **Perwakilan Calon Penumpang** | Tim Penguji Pengguna | Pengguna Uji Lapangan | **ACCEPTED** | *(Signed UAT Sheet)* |

---

## 5. KESIMPULAN & DEKLARASI RILIS

> **PERNYATAAN DEKLARASI:**  
> Dengan ini, platform **FlyDine Bandara Internasional Juanda (SUB)** dinyatakan telah memenuhi seluruh standar kualifikasi mutu perangkat lunak, lulus dari seluruh pengujian fungsional maupun batasan regulasi bandara, dan **RESMI DISETUJUI UNTUK DIRILIS SEBAGAI PRODUK MINIMUM VIABLE PRODUCT (MVP)**.

