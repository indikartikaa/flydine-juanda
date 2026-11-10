# FlyDine — Sistem Pre-Order & Antrean Digital Kuliner Bandara Internasional Juanda

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=flat-square&logo=alpinedotjs&logoColor=black)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Pest](https://img.shields.io/badge/Tested_with-Pest_%2F_PHPUnit-F28D1A?style=flat-square)

**FlyDine** adalah platform web *pre-order* makanan dan manajemen antrean digital terpusat untuk ekosistem *Food & Beverage (F&B)* di **Bandar Udara Internasional Juanda Surabaya (Terminal 1 & Terminal 2)**. Sistem ini dirancang untuk memangkas waktu tunggu fisik penumpang sebelum *boarding*, mempermudah operasional dapur mitra *tenant*, serta menyediakan dasbor pemantauan kinerja layanan (*Service Level Agreement*) bagi manajemen bandara.

---

## Fitur Utama

### 1. Modul Pelanggan (Penumpang & Pengunjung)
- **Katalog Multi-Terminal & Zonasi Keamanan:** Eksplorasi menu dari 76 gerai F&B di Terminal 1 dan Terminal 2 dengan pemisahan otomatis area *Landside* (publik) dan *Airside* (ruang tunggu *boarding*).
- **Keranjang Belanja Asinkronus (AJAX):** Penambahan dan penyesuaian porsi menu secara *real-time* tanpa memuat ulang halaman, dilengkapi validasi satu restoran per transaksi (*Single-Tenant Cart Guard*).
- **Validasi Jadwal Penerbangan (*Boarding Guard*):** Memastikan sisa waktu menuju jam *boarding* mencukupi durasi penyiapan makanan ($\ge \max(15\text{ menit}, \text{waktu masak menu})$) untuk mencegah risiko tertinggal pesawat.
- **Opsi Pengambilan Fleksibel:** Mendukung pengambilan mandiri di konter (*Self Pick-up*) maupun pengantaran oleh *porter* ke titik *Gate* keberangkatan dalam terminal dan zona keamanan yang sama.
- **Pelacakan Status *Real-Time* & *E-Receipt*:** Pemantauan progres pesanan mulai dari pembayaran, proses masak di dapur, hingga siap diambil, lengkap dengan struk digital ber-QR Code.
- **Pusat Bantuan & Tiket Komplain:** Pengajuan dan pelacakan status keluhan pesanan (`CMP-XXXXXX`) secara mandiri menggunakan kode pesanan atau nomor WhatsApp.

### 2. Modul Mitra Restoran (*Tenant Staff*)
- **Manajemen Antrean Dapur *Real-Time*:** Pembaruan pesanan masuk secara otomatis (*live polling*) dengan urutan transisi status terkunci (*State Machine*: `menunggu` $\rightarrow$ `diproses` $\rightarrow$ `siap` $\rightarrow$ `selesai`).
- **Katalog & Stok Harian Mandiri:** Pengelolaan ketersediaan menu, kuota stok porsi harian, estimasi waktu masak (*preparation time*), serta jam buka/tutup operasional gerai.
- **Rekapitulasi & Riwayat Transaksi:** Pemantauan omset harian dan riwayat penyelesaian pesanan per gerai secara terisolasi (*Multi-Tenant Isolation*).

### 3. Modul Admin Operasional & Eksekutif Bandara (*Admin Ops*)
- **Executive Dashboard (SIE):** Visualisasi *Key Performance Indicators (KPI)* utama bandara meliputi volume pesanan harian, rasio okupansi *tenant*, distribusi jam sibuk (*Peak Hours*), dan kepatuhan SLA ($\le 15$ menit) berbasis `Chart.js`.
- **Analitik Multidimensi (*Drill-Down*) & Simulasi *What-If*:** Pembedahan data transaksi lintas dimensi serta kalkulator simulasi kapasitas dapur saat lonjakan trafik penumpang.
- **Otomatisasi ETL & Ekspor Laporan:** Agregasi data harian otomatis ke tabel ringkasan (`daily_tenant_summaries`) serta ekspor laporan resmi ke format **PDF** dan **Excel (`.xls`)**.
- **CRM, *Helpdesk* Komplain, & *Fraud Monitoring*:** Segmentasi loyalitas pelanggan, penanganan tiket keluhan, log audit aktivitas terpusat (`activity_logs`), serta deteksi otomatis pola pembatalan berulang (*spam order*).

---

## Teknologi yang Digunakan

- **Backend:** PHP 8.2+, Laravel 12.x
- **Frontend:** Blade Templating Engine, Tailwind CSS 3.x, Alpine.js 3.x, Vite
- **Visualisasi & Dokumen:** Chart.js 4.x, Barryvdh Laravel DomPDF
- **Database:** MySQL 8.0+ / MariaDB (dilengkapi *Native SQL Triggers*, *Stored Procedure*, & *Composite Indexes*)
- **Pengujian:** Pest PHP / PHPUnit

---

## Prasyarat (*Requirements*)

Sebelum menjalankan proyek ini di lingkungan lokal, pastikan perangkat telah terpasang:
- **PHP** >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `gd`, `xml`, `ctype`, `json`)
- **Composer** >= 2.x
- **Node.js** >= 18.x & **NPM**
- **MySQL** >= 8.0 atau **MariaDB** >= 10.6 (dapat menggunakan Laragon / XAMPP)

---

## Cara Instalasi & Penggunaan

### 1. Kloning Repositori & Pasang Dependensi
```bash
git clone <url-repositori-anda> flydine-juanda
cd flydine-juanda

composer install
npm install
```

### 2. Konfigurasi Variabel Lingkungan (*Environment Variables*)
Salin berkas `.env.example` menjadi `.env` lalu buat *application key*:
```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi koneksi basis data di dalam berkas `.env`:
```env
APP_NAME="FlyDine Juanda"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flydine_juanda
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrasi Basis Data & *Data Seeding*
Jalankan perintah migrasi beserta *seeder* untuk membuat struktur tabel, memasang *SQL Triggers & Stored Procedure*, serta mengisi data 76 *tenant* dan katalog menu Terminal 1 & 2:
```bash
php artisan migrate --seed
```

*(Opsional)* Untuk menjalankan agregasi laporan ETL harian atau membatalkan pesanan kedaluwarsa secara manual:
```bash
php artisan app:run-daily-etl
php artisan orders:cancel-expired
```

### 4. Kompilasi Aset & Menjalankan Server
```bash
npm run build
php artisan serve
```
Akses aplikasi melalui peramban di alamat: `http://127.0.0.1:8000`

### 5. Akun Demo Pengujian (Hasil *Seeder*)
| Peran Pengguna | Email Login | Kata Sandi | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Admin Operasional** | `admin.ops@flydine.test` | `password` | Dashboard Eksekutif, Analitik Drill-Down & What-If, Manajemen Tenant, Komplain, & Fraud Monitoring |
| **Staf Mitra Tenant** | `staff.aw-fb-t1-01-01@flydine.test` | `juanda123` | Dashboard Dapur, Pemrosesan Antrean Pesanan, & Manajemen Stok Menu |
| **Pelanggan (Publik)** | *(Tanpa Login)* | — | Pemesanan langsung dari halaman beranda `/` |

### 6. Menjalankan Pengujian Otomatis (*Automated Testing*)
```bash
php artisan test
```

---

## Kontributor & Lisensi

- **Tim Pengembang:**
  - **Masyito Indi Kartika** (Program Studi Sistem Informasi)
  - **Dhidan Fachriza Zakaria** (Program Studi Sistem Informasi)
- **Mitra Proyek Magang:** PT Angkasa Pura Indonesia — Kantor Regional IV Bandar Udara Internasional Juanda Surabaya (*Airport Technology Department*)
- **Lisensi:** Proyek ini dikembangkan untuk keperluan implementasi sistem dan akademik di lingkungan PT Angkasa Pura Indonesia. Berbasis kerangka kerja Laravel di bawah lisensi [MIT License](https://opensource.org/licenses/MIT).
