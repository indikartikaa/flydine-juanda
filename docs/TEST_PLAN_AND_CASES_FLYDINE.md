# SOFTWARE TEST PLAN & TEST CASES SPESIFIKASI (LENGKAP)
## Sistem FlyDine Juanda International Airport (SUB)
**Versi:** 1.0 (Comprehensive Release)  
**Tanggal Dokumen:** 2026-11-04  
**Total Kasus Uji:** 130 Skenario Uji Fungsional & Non-Fungsional  
**Aplikasi:** FlyDine Juanda (Pemesanan Makanan & Minuman Pra-Penerbangan Bandara Juanda)

---

## DAFTAR ISI
1. [Membedah Alur Bisnis FlyDine & Batasan Operasional Bandara Juanda](#1-membedah-alur-bisnis-flydine--batasan-operasional-bandara-juanda)
   - 1.1 Profil Pengguna (Penumpang Pesawat vs Pengunjung Umum)
   - 1.2 Batasan Zonasi Keamanan Bandara (Airside vs Landside & Lintas Terminal)
   - 1.3 Formula Kalkulasi Biaya (Subtotal, Pajak/PB1, Diskon, & Ongkos Antar Porter)
   - 1.4 Aturan Penjadwalan Boarding Pesawat & SLA Penyiapan Dapur
   - 1.5 Mesin Auto-Cancel Dinamis (Passive vs Active Engine)
   - 1.6 State Machine Transisi Pesanan & Pemicu (Trigger) Status "Menuju Tenant"
2. [Dokumen Rencana Uji (Software Test Plan)](#2-dokumen-rencana-uji-software-test-plan)
   - 2.1 Ruang Lingkup Pengujian (In-Scope & Out-of-Scope)
   - 2.2 Lingkungan Uji (Test Environment Specification)
   - 2.3 Kriteria Lulus Uji (Exit Criteria & Defect Severity Matrix)
   - 2.4 Pembagian Jadwal & Peran Tim Penguji
3. [Tabel Matriks Kasus Uji (130 Skenario Uji Fungsional)](#3-tabel-matriks-kasus-uji-130-skenario-uji-fungsional)
   - 3.1 Fitur Utama 1: Alur Pemesanan Menu, Keranjang, Kalkulasi Biaya, & Airport Zoning (TC-ORD-001 s.d. TC-ORD-045)
   - 3.2 Fitur Utama 2: Logika Auto-Cancel, Timer, & Sinkronisasi Jadwal Boarding (TC-CNL-001 s.d. TC-CNL-035)
   - 3.3 Fitur Utama 3: Pemicu (Trigger) "Menuju Tenant" & State Machine Status (TC-STA-001 s.d. TC-STA-030)
   - 3.4 Fitur Pendukung: Validasi Input (BVA/EP), Keamanan, & Anti-Fraud (TC-SEC-001 s.d. TC-SEC-020)

---

## 1. MEMBEDAH ALUR BISNIS FLYDINE & BATASAN OPERASIONAL BANDARA JUANDA

### 1.1 Profil Pengguna
1. **Penumpang Pesawat (Passenger):**
   - Memiliki tiket penerbangan dengan atribut: `flight_number` (cth: GA-312, JT-640), `gate` (Gate 1–12), dan `boarding_time` (jam keberangkatan).
   - Berada di dalam ruang tunggu steril (*Airside*) setelah melewati pos pemeriksaan keamanan SCP-2.
   - Memerlukan kepastian makanan selesai dimasak sebelum waktu gerbang boarding ditutup.
2. **Pengunjung / Penjemput / Pengantar Umum (Visitor):**
   - Tidak memiliki tiket penerbangan.
   - Hanya memiliki akses di area publik (*Landside* / Lobby keberangkatan luar / area parkir).
   - Dilarang memilih titik antar atau memesan tenant yang berada di dalam zona steril Airside.

### 1.2 Batasan Zonasi Keamanan Bandara (Security Boundary)
- **Zona Landside:** Area sebelum Security Check Point 1 (SCP-1).
- **Zona Airside:** Area steril boarding lounge setelah Security Check Point 2 (SCP-2).
- **Aturan Porter Antar:**
  * Porter bandara bertugas di zona masing-masing dan **TIDAK DIIZINKAN** melintasi pos pemeriksaan sinar-X keamanan bandara sambil membawa pesanan makanan antara Landside dan Airside.
  * Pesanan antar lintas Terminal 1 (Domestik umum) dan Terminal 2 (Internasional & Garuda) **DITOLAK** karena jarak fisik dan batas otoritas bandara yang terpisah.

### 1.3 Formula Kalkulasi Biaya
Sistem menerapkan formula kalkulasi multi-komponen:
$$\text{Subtotal Item} = \sum_{i=1}^{n} (\text{Harga Produk Terkini di DB}_i \times \text{Kuantitas}_i)$$
$$\text{Diskon Promo} = \begin{cases} 
\text{Nominal Diskon Flat}, & \text{jika promo voucher tetap} \\
\min(\text{Subtotal} \times \text{Persentase}, \text{Maksimal Diskon}), & \text{jika diskon persen mitra bank} \\
0, & \text{jika tanpa promo}
\end{cases}$$
$$\text{Dasar Pengenaan Pajak (DPP)} = \text{Subtotal Item} - \text{Diskon Promo}$$
$$\text{Pajak Restoran (PB1 10\%)} = \begin{cases} 
0, & \text{jika harga produk disepakati sudah All-Inclusive (Nett)} \\
\text{DPP} \times 10\%, & \text{jika pajak dihitung terpisah}
\end{cases}$$
$$\text{Biaya Antar Porter} = \begin{cases} 
0, & \text{jika metode } = \text{'ambil\_sendiri'} \\
\text{tenant.delivery\_fee (cth: Rp 8.000)}, & \text{jika metode } = \text{'diantar'}
\end{cases}$$
$$\text{Total Pembayaran} = \text{DPP} + \text{Pajak Restoran} + \text{Biaya Antar Porter}$$

*Catatan Keamanan:* Seluruh variabel harga, stok, dan tarif dihitung ulang di level server melalui controller dan database; sistem tidak pernah mempercayai nilai rupiah yang dikirim via payload JavaScript browser.

### 1.4 Aturan Penjadwalan Boarding Pesawat & SLA Dapur
- Setiap produk memiliki atribut `preparation_time` (durasi penyiapan di dapur tenant, rentang 5 s.d. 35 menit).
- Nilai SLA penyiapan pesanan dihitung dari menu terlama: $\text{MaxPrepTime} = \max(\text{preparation\_time}_i)$.
- Batas toleransi minimum boarding: $\text{MinBoardingGap} = \max(15, \text{MaxPrepTime})$ menit.
- **Pencegahan Ketinggalan Pesawat:** Jika penumpang memasukkan jadwal boarding dengan selisih waktu $< \text{MinBoardingGap}$ dari jam checkout, sistem langsung menolak pesanan dan menampilkan peringatan risiko tertinggal pesawat.
- **Dukungan Penerbangan Dini Hari (Cross-Midnight):** Jika pemesanan dilakukan menjelang tengah malam (misal 23:40 WIB) untuk penerbangan 00:35 WIB (hari berikutnya), sistem mendeteksi loncatan hari (+1440 menit) sehingga pesanan tidak ditolak sebagai "jam lampau".

### 1.5 Mesin Auto-Cancel Dinamis
1. **Timer Standar (Pengunjung):** Pesanan otomatis dibatalkan dalam waktu 15 menit jika belum dibayar (`now()->addMinutes(15)`).
2. **Timer Penumpang (Boarding Synced):** Jika jam boarding pesawat lebih awal dari 15 menit standar tersebut, nilai `auto_cancel_at` dipersingkat agar pesanan otomatis hangus tepat saat pesawat boarding.
3. **Mekanisme Eksekusi Ganda:**
   - **Active Scheduler:** Perintah console `php artisan orders:cancel-expired` berjalan setiap 60 detik melalui Laravel Scheduler / Windows Task Scheduler.
   - **Passive Fallback:** Sistem secara langsung mengeksekusi fungsi pembatalan instan saat customer mengakses struk (`/receipt`), mem-polling status AJAX (`/tracking/{order}/status`), atau saat customer yang sama mencoba checkout lagi.
4. **Pemulihan Stok Otomatis:** Pembatalan pesanan (oleh sistem atau customer) memicu pengembalian kuota stok produk secara otomatis via MySQL Trigger `trg_after_order_status_update` atau model event fallback.

### 1.6 State Machine Transisi Pesanan & Trigger "Menuju Tenant"
- **Siklus Status Resmi:**
  `menunggu` (unpaid) $\rightarrow$ `diproses` (paid & tenant cooking) $\rightarrow$ `siap` (ready at counter/porter) $\rightarrow$ `selesai` (delivered).
- **Kondisi Khusus:** `menunggu` $\rightarrow$ `dibatalkan` (auto-cancel / customer cancel / tenant reject).
- **State Machine Strict Forward:**
  * Dilarang melompati status (misal dari `menunggu` langsung ke `selesai`).
  * Dilarang membalikkan status (misal dari `selesai` kembali ke `diproses`).
  * Tenant dilarang memproses pesanan yang belum berstatus `is_paid = true`.
- **Trigger Waktu SLA Database:**
  * Status menjadi `siap` $\rightarrow$ MySQL Trigger mengisi timestamp `ready_at = NOW()`.
  * Status menjadi `selesai` $\rightarrow$ MySQL Trigger mengisi timestamp `completed_at = NOW()`.
- **Trigger Aksi "Saya Menuju Tenant":**
  * Ketika makanan berstatus `siap`, penumpang di ruang tunggu menekan tombol aksi "Saya Menuju Tenant".
  * Sistem mengaktifkan field `heading_to_tenant_at = NOW()`.
  * Dashboard kasir tenant menerima highlight sinyal visual agar staf kasir segera meletakkan pesanan di meja penyerahan cepat (*quick pickup counter*).

---

## 2. DOKUMEN RENCANA UJI (SOFTWARE TEST PLAN)

### 2.1 Ruang Lingkup Pengujian (Test Scope)

#### A. Dalam Ruang Lingkup (In-Scope):
1. **Modul Antarmuka Pelanggan (Customer Frontend):**
   - Katalog menu berdasarkan Terminal (T1 & T2), tenant, kategori, dan bilah pencarian cerdas.
   - Interaksi keranjang belanja (tambah, kurang, hapus, validasi multi-varian, custom note).
   - Formulir checkout: kalkulasi biaya, verifikasi ketersediaan stok, validasi nomor HP, seleksi metode ambil (Self-pickup vs Diantar Porter), validasi batas zonasi keamanan bandara, validasi nomor penerbangan & waktu boarding.
   - Halaman pelacakan live status (`/tracking/{order}`), polling asinkron AJAX status, dan pemicu aksi "Saya Menuju Tenant".
   - Fitur pembatalan mandiri sebelum pembayaran dan unduh struk digital (`/receipt/{order}`).
2. **Modul Pengelolaan Tenant (Tenant Portal):**
   - Dashboard antrean pesanan real-time.
   - Transisi status pesanan (`diproses`, `siap`, `selesai`, `ditolak`).
   - Manajemen ketersediaan produk (*toggle is_available*, update stok, edit waktu persiapan).
   - Pengaturan jam buka-tutup restoran.
3. **Modul Mesin Backend & Integritas Basis Data:**
   - Eksekusi artisan command scheduler `orders:cancel-expired`.
   - Eksekusi MySQL Native Trigger (`trg_after_order_item_insert_potong_stok`, `trg_before_order_update_sla_guard`, `trg_after_order_status_update`).
   - Proteksi keamanan: Active Order Limit (maks 5 pesanan pending), pencegahan spam pembatalan, mitigasi SQLi, dan XSS.
4. **Pengujian Non-Fungsional:**
   - Black-Box Testing: Boundary Value Analysis (BVA) dan Equivalence Partitioning (EP) pada seluruh input formulir transaksi.
   - REST API Testing: Kode status HTTP (200, 400, 403, 404, 422, 500) dan validasi skema JSON respons.
   - Load Testing JMeter: Beban puncak jam sibuk bandara (100–500 concurrent users).

#### B. Di Luar Ruang Lingkup (Out-of-Scope):
- Transaksi riil debit/kredit perbankan BI-FAST / Visa / Mastercard asli (menggunakan skema Virtual Account & QRIS Simulator internal).
- Integrasi sensor GPS live tracking porter di landasan pacu bandara.

---

### 2.2 Lingkungan Uji (Test Environment)

| Komponen | Spesifikasi Lingkungan Uji |
| :--- | :--- |
| **Sistem Operasi** | Windows 11 Home / Pro (Workstation Pengujian) & Ubuntu Linux 22.04 LTS (CI/Server) |
| **Runtime & Framework** | PHP 8.2.x, Laravel Framework 11.x |
| **Basis Data Utama** | MySQL 8.0 Community Server (Mendukung Stored Procedures & Triggers) |
| **Basis Data Pengujian Unit** | SQLite 3 In-Memory (`:memory:`) untuk pengujian otomatis cepat (`RefreshDatabase`) |
| **Browser Web** | Google Chrome v125+ (Desktop & Emulasi Mobile 390x844px), Mozilla Firefox, Microsoft Edge |
| **Tools Pengujian API** | Postman Desktop App v12.x / Newman CLI |
| **Tools Uji Beban** | Apache JMeter 5.6.x (Didukung Java OpenJDK 17+) |
| **Alat Bantu Pelaporan Bug** | Trello / Jira Kanban Board & Spreadsheet Rekapitulasi Defect |

---

### 2.3 Kriteria Lulus Uji (Exit Criteria & Defect Severity)

#### Kriteria Kelulusan Pengujian (Exit Criteria):
1. **Tingkat Kelulusan Kasus Uji:** $\ge 98\%$ dari 130 skenario uji berstatus **PASS**.
2. **Toleransi Cacat Terbuka (Open Bugs):**
   - **Severity 1 (Blocker):** 0 cacat terbuka (wajib 100% diperbaiki).
   - **Severity 2 (Critical):** 0 cacat terbuka (wajib 100% diperbaiki).
   - **Severity 3 (Major):** Maksimal 2 cacat dengan solusi mitigasi sementara (*workaround*).
   - **Severity 4 (Minor):** Maksimal 5 cacat terkait kosmetik tampilan atau typo bahasa.
3. **Integritas Finansial & Kuota:** 100% perhitungan subtotal, diskon, ongkos antar, serta stok keluar-masuk harus presisi tanpa deviasi nominal.
4. **Metrik Performa:** Rata-rata waktu respons endpoint backend $\le 800\text{ ms}$ saat diuji pada 100 concurrent users di JMeter, dengan tingkat galat (*error rate*) $< 1\%$.

#### Matriks Klasifikasi Keparahan Cacat (Defect Severity Matrix):
- **S1 - Blocker:** Kerusakan total yang menghentikan pengujian atau transaksi (contoh: server crash, total harga Rp 0, kuota stok berkurang minus, uang terpotong tetapi order lenyap).
- **S2 - Critical:** Fitur inti gagal berfungsi (contoh: pesanan kedaluwarsa tidak dibatalkan, tombol "Menuju Tenant" tidak merespons, porter bisa melintasi batas Airside-Landside).
- **S3 - Major:** Fitur berjalan namun menyalahi aturan bisnis atau validasi (contoh: nomor HP 8 digit berhasil lolos, jam boarding terlewat tidak memunculkan pesan spesifik).
- **S4 - Minor:** Gangguan kosmetik UI/UX (contoh: tombol tidak rata tengah di layar ponsel kecil, teks terpotong pada orientasi horizontal).

---

### 2.4 Pembagian Jadwal & Peran Tim Penguji

| Jadwal (Timeline) | Fase Pengujian | Aktivitas Utama & Output yang Dihasilkan |
| :---: | :--- | :--- |
| **Hari 1** | Fase 1: Perencanaan & Test Plan | Membedah alur FlyDine Juanda, menetapkan batasan zonasi bandara, menyusun 130 matriks kasus uji. |
| **Hari 2–3** | Fase 2: Unit & Integration Testing | Menulis skrip pengujian otomatis di `tests/Feature/` dan `tests/Unit/`, memvalidasi kalkulasi dan state machine. |
| **Hari 4** | Fase 3: Black-Box & API Testing | Eksekusi manual BVA form web, uji API Postman (status code, struktur JSON, validasi parameter). |
| **Hari 5** | Fase 4: Uji Kinerja Beban (JMeter) | Eksekusi file `.jmx`, simulasi 100–500 pengguna bersamaan di jam sibuk Bandara Juanda, identifikasi bottleneck. |
| **Hari 6** | Fase 5: Bug Tracking & Sesi UAT | Koordinasi perbaikan bug di papan Trello/Jira, uji regresi (*re-testing*), uji coba langsung bersama calon penumpang dan tenant. |
| **Hari 7** | Fase 6: Dokumen Laporan & Rilis MVP | Penyusunan Software Testing Document resmi, pembuatan User Manual bergambar, deklarasi kesiapan peluncuran MVP. |

---

## 3. TABEL MATRIKS KASUS UJI (130 SKENARIO UJI FUNGSIONAL)

### 3.1 Fitur Utama 1: Alur Pemesanan Menu, Keranjang, Kalkulasi Biaya, & Airport Zoning (45 Kasus Uji)

| ID Kasus Uji | Modul Fitur | Skenario Pengujian | Prosedur Uji / Input Data (BVA/EP) | Hasil yang Diharapkan (Expected Result) | Severity |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-ORD-001** | Keranjang | BVA: Tambah porsi menu kuantitas = 1 | Klik '+' 1 kali pada menu berharga Rp 25.000 | Item masuk keranjang, qty = 1, subtotal = Rp 25.000. | High |
| **TC-ORD-002** | Keranjang | BVA: Ubah kuantitas menjadi nol (0) | Klik '-' saat qty = 1 atau ketik angka 0 di form | Item otomatis terhapus dari keranjang, subtotal berkurang. | Medium |
| **TC-ORD-003** | Keranjang | BVA: Input kuantitas angka negatif | Kirim payload request kuantitas `-1` atau `-5` | Ditolak oleh validasi backend (422 Unprocessable Entity). | High |
| **TC-ORD-004** | Keranjang | BVA: Pesan kuantitas tepat sama dengan sisa stok | Stok menu = 5, masukkan kuantitas pesanan 5 | Pesanan berhasil masuk ke keranjang, tombol '+' dinonaktifkan. | High |
| **TC-ORD-005** | Keranjang | BVA: Pesan kuantitas melebihi sisa stok menu | Stok menu = 5, coba masukkan kuantitas 6 | Ditolak dengan notifikasi "Stok untuk menu ini tidak mencukupi (tersedia 5)". | High |
| **TC-ORD-006** | Keranjang | EP: Kuantitas berupa string teks alfabet | Kirim request kuantitas `"dua"` atau `"sepuluh"` | Ditolak oleh validasi: kuantitas harus berupa bilangan bulat. | Medium |
| **TC-ORD-007** | Keranjang | EP: Kuantitas berupa karakter simbol khusus | Kirim request kuantitas `@#$` atau `<script>` | Form web menolak, payload dibersihkan/ditolak validasi. | High |
| **TC-ORD-008** | Keranjang | EP: Kuantitas berupa angka pecahan/desimal | Kirim request kuantitas `1.5` atau `2.75` | Ditolak validasi integer: kuantitas tidak boleh desimal. | Medium |
| **TC-ORD-009** | Keranjang | Validasi menu nonaktif (`is_available = false`) | Tenant menonaktifkan menu, pelanggan coba tambah | Tombol pesan disable, request API menghasilkan respons error 400. | High |
| **TC-ORD-010** | Keranjang | Multi-tenant restriction (Pencegahan campur tenant)| Keranjang berisi menu Resto A, klik pesan menu Resto B | Muncul dialog konfirmasi: ganti restoran akan mereset keranjang belanja lama. | High |
| **TC-ORD-011** | Keranjang | Pemilihan opsi varian produk | Pilih menu dengan varian ukuran (Regular, Large +5000) | Harga subtotal bertambah sesuai nominal surcharge varian terpilih. | High |
| **TC-ORD-012** | Keranjang | Penambahan catatan khusus (Custom Notes) | Masukkan catatan "Jangan pakai daun bawang, pisahkan sambal" | Catatan tersimpan di session cart dan diteruskan ke database order items. | Medium |
| **TC-ORD-013** | Keranjang | BVA Catatan Khusus: Karakter batas panjang | Masukkan catatan 255 karakter vs 256 karakter | 255 karakter berhasil disimpan; > 255 karakter ditolak atau dipotong aman. | Medium |
| **TC-ORD-014** | Keranjang | Hapus salah satu item dari keranjang multi-item | Keranjang memiliki 3 menu berbeda, klik ikon hapus pada menu ke-2 | Menu ke-2 terhapus, subtotal dihitung ulang dari 2 menu yang tersisa. | High |
| **TC-ORD-015** | Keranjang | Kosongkan seluruh keranjang (Clear Cart) | Klik tombol "Kosongkan Keranjang" | Seluruh session cart terhapus, tampilan keranjang beralih ke state kosong. | Medium |
| **TC-ORD-016** | Checkout | Anti-Price Tampering pada sisi klien (Client Tampering)| Ubah parameter `price=1000` via inspect network request | Backend mengabaikan nilai harga klien dan mengambil harga resmi dari DB. | Critical |
| **TC-ORD-017** | Checkout | Kalkulasi Subtotal multi-item secara presisi | Pesan 3 Roti O (@14.000) + 2 Kopi (@18.000) | Subtotal terhitung tepat Rp 78.000 ($42.000 + 36.000$). | Critical |
| **TC-ORD-018** | Checkout | Kalkulasi Biaya Antar: Metode Self-Pickup | Pilih metode "Ambil Sendiri di Tenant" | Biaya antar tercatat Rp 0, total bayar = subtotal. | High |
| **TC-ORD-019** | Checkout | Kalkulasi Biaya Antar: Metode Diantar Porter | Pilih metode "Diantar ke Lokasi Gate" (fee = 8000) | Biaya antar Rp 8.000 ditambahkan ke total akhir transaksi. | High |
| **TC-ORD-020** | Checkout | Kalkulasi Diskon Promo Flat (Voucher Kupon) | Subtotal Rp 100.000, terapkan voucher flat Rp 15.000 | Total bayar berkurang menjadi Rp 85.000 + biaya antar. | High |
| **TC-ORD-021** | Checkout | Kalkulasi Diskon Persentase dengan Batas Maksimal | Subtotal Rp 200.000, diskon mitra 20% maks Rp 25.000 | Diskon yang terpotong tepat batas plafon Rp 25.000 (bukan Rp 40.000). | High |
| **TC-ORD-022** | Checkout | Kalkulasi Bebas Ongkir (Free Delivery Threshold) | Subtotal melampaui Rp 150.000 pada tenant promosi free delivery | Ongkos porter otomatis terdiskon menjadi Rp 0. | Medium |
| **TC-ORD-023** | Airport Zoning | Batasan Porter: Tenant Airside ke Titik Antar Landside | Tenant di T1 Gate 3 Airside, titik antar Lobby Publik | Ditolak: "Layanan porter tidak dapat melintasi pemeriksaan keamanan". | Blocker |
| **TC-ORD-024** | Airport Zoning | Batasan Porter: Tenant Landside ke Titik Antar Airside | Tenant di T1 Lobby Landside, titik antar Gate 5 Airside | Ditolak oleh sistem keamanan batas zonasi bandara. | Blocker |
| **TC-ORD-025** | Airport Zoning | Pengantaran Sah: Tenant Airside ke Gate Airside | Tenant di Gate 2 Airside, titik antar Gate 4 Airside (Sama-sama T1) | Berhasil divalidasi, pesanan diterima oleh porter zona steril. | High |
| **TC-ORD-026** | Airport Zoning | Pengantaran Sah: Tenant Landside ke Titik Antar Landside | Tenant di Lobby T1, titik antar Drop Zone Keberangkatan T1 | Berhasil divalidasi, pesanan diterima oleh porter zona publik. | High |
| **TC-ORD-027** | Airport Zoning | Pembatasan Lintas Terminal (Cross-Terminal Delivery) | Tenant di Terminal 1, pelanggan pilih titik antar Terminal 2 | Ditolak: porter bandara tidak melayani pengantaran antar-terminal. | Blocker |
| **TC-ORD-028** | Airport Zoning | Tipe Pengguna: Pengunjung memesan tenant Airside | Pilih profil "Pengunjung", lalu coba pesan di restoran Airside | Akses menu dicegah atau validasi checkout menolak pengunjung masuk area steril. | Blocker |
| **TC-ORD-029** | Airport Zoning | Tipe Pengguna: Penumpang memesan tenant Airside | Pilih profil "Penumpang", isi data penerbangan valid di Airside | Checkout berhasil diproses sesuai terminal keberangkatan. | High |
| **TC-ORD-030** | Jam Buka | Pemesanan pada Restoran di Luar Jam Operasional | Checkout pada pukul 23:30 (jam operasional resto 06:00–22:00) | Sistem menolak checkout: "Restoran sedang tutup, silakan pesan saat buka". | High |
| **TC-ORD-031** | Jam Buka | BVA: Pemesanan persis di menit jam buka operasional | Resto buka pukul 06:00, checkout dieksekusi tepat 06:00 | Transaksi berhasil diproses. | Medium |
| **TC-ORD-032** | Jam Buka | BVA: Pemesanan 1 menit sebelum jam tutup restoran | Resto tutup pukul 22:00, checkout dieksekusi tepat 21:59 | Transaksi berhasil diproses selama waktu penyiapan masih mencukupi. | Medium |
| **TC-ORD-033** | Checkout | Sinkronisasi Kenaikan Harga Mendadak saat Checkout | Tenant menaikkan harga menu saat pelanggan masih di halaman cart | Saat tombol checkout ditekan, total langsung menyesuaikan harga database baru. | High |
| **TC-ORD-034** | Checkout | Pemotongan Kuota Stok di Database saat Order Masuk | Stok awal 10 porsi, pelanggan checkout 2 porsi | Record order terbuat, kuota stok produk di tabel `products` tersisa 8. | Critical |
| **TC-ORD-035** | Checkout | Pembelian Menghabiskan Stok Pas (Stok Menjadi Nol) | Stok awal 3 porsi, dibeli 3 porsi | Stok menjadi 0, menu otomatis ditandai *out-of-stock* untuk pembeli lain. | High |
| **TC-ORD-036** | Checkout | Pencegahan Pembelian Stok Bersamaan (Race Condition) | 2 pengguna bersamaan checkout menu terakhir yang sisa 1 | Pengguna 1 berhasil, Pengguna 2 mendapat respons "Stok telah habis". | Critical |
| **TC-ORD-037** | Checkout | Penerbitan Nomor Kode Pesanan Unik (`order_code`) | Lakukan 10 transaksi checkout berturut-turut | Seluruh 10 transaksi memiliki kode unik berawalan `ORD-` tanpa ada duplikasi. | High |
| **TC-ORD-038** | Checkout | Kelengkapan Snapshot Data Produk di `order_items` | Checkout menu bernama "Paket Nasi Gudeg Telur" | Tabel `order_items` menyimpan `product_name_snapshot`, `unit_price`, dan `subtotal`. | High |
| **TC-ORD-039** | Checkout | Integritas Nama Snapshot saat Nama Produk di DB Berubah | Tenant mengganti nama menu di admin setelah transaksi dibuat | Struk pesanan lama tetap menampilkan nama menu historis asli saat transaksi. | High |
| **TC-ORD-040** | Checkout | Pemilihan Metode Pembayaran QRIS Dinamis | Pilih metode bayar QRIS pada halaman checkout | Halaman tracking menampilkan gambar QRIS aktif dengan batas waktu bayar. | High |
| **TC-ORD-041** | Checkout | Pemilihan Metode Pembayaran Virtual Account Mandiri | Pilih metode pembayaran Virtual Account Mandiri | Terbit nomor VA unik diawali kode identifikasi bank Mandiri. | High |
| **TC-ORD-042** | Checkout | Pemilihan Metode Pembayaran Virtual Account BCA | Pilih metode pembayaran Virtual Account BCA | Terbit nomor VA unik diawali kode identifikasi bank BCA. | High |
| **TC-ORD-043** | Checkout | Pemilihan Metode Pembayaran Virtual Account BRI | Pilih metode pembayaran Virtual Account BRI | Terbit nomor VA unik diawali kode identifikasi bank BRI. | High |
| **TC-ORD-044** | Checkout | Pemilihan Metode Pembayaran Virtual Account BNI | Pilih metode pembayaran Virtual Account BNI | Terbit nomor VA unik diawali kode identifikasi bank BNI. | High |
| **TC-ORD-045** | Checkout | Verifikasi Nilai Total pada Halaman Ringkasan Struk | Buka halaman struk `/receipt/{order}` setelah order dibuat | Nilai subtotal, ongkir, diskon, dan grand total identik dengan data database. | High |

---

### 3.2 Fitur Utama 2: Logika Auto-Cancel, Timer, & Sinkronisasi Jadwal Boarding (35 Kasus Uji)

| ID Kasus Uji | Modul Fitur | Skenario Pengujian | Prosedur Uji / Input Data (BVA/EP) | Hasil yang Diharapkan (Expected Result) | Severity |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-CNL-001** | Timer Default | Penetapan Timer Bawaan Pengunjung Umum (15 Menit) | Checkout dengan `customer_type: 'pengunjung'` | Nilai `auto_cancel_at` diset tepat 15 menit dari timestamp `ordered_at`. | High |
| **TC-CNL-002** | Timer Default | Tampilan Penghitung Mundur (Countdown Timer) UI | Buka halaman tracking order belum bayar | Terdapat visual timer digital berjalan mundur dari 15:00 menuju 00:00. | Medium |
| **TC-CNL-003** | Boarding SLA | BVA: Jam Boarding Lampau (Lebih awal dari sekarang) | Waktu sekarang 14:00, isi jam boarding 13:30 | Ditolak: "Jadwal waktu boarding yang Anda masukkan sudah terlewat". | High |
| **TC-CNL-004** | Boarding SLA | BVA: Jam Boarding Tepat Sama dengan Jam Sekarang | Waktu sekarang 14:00, isi jam boarding 14:00 | Ditolak: waktu boarding tidak memenuhi syarat minimum persiapan dapur. | High |
| **TC-CNL-005** | Boarding SLA | BVA: Jam Boarding Kurang 1 Menit dari Batas (14 Menit)| Waktu sekarang 10:00, jam boarding 10:14 (selisih 14m) | Ditolak: "Waktu boarding terlalu dekat (kurang dari 15 menit dari sekarang)". | Critical |
| **TC-CNL-006** | Boarding SLA | BVA: Jam Boarding Tepat pada Batas Minimum (15 Menit) | Waktu sekarang 10:00, jam boarding 10:15 (selisih 15m) | Diterima oleh sistem, order berhasil diterbitkan. | High |
| **TC-CNL-007** | Boarding SLA | BVA: Jam Boarding Lebih 1 Menit dari Batas (16 Menit) | Waktu sekarang 10:00, jam boarding 10:16 (selisih 16m) | Diterima oleh sistem tanpa kendala validasi. | High |
| **TC-CNL-008** | Dynamic SLA | Waktu Masak Menu Lebih Lama dari 15 Menit | Menu steak dengan `preparation_time = 25` menit | Batas minimum boarding otomatis melonjak menjadi 25 menit. | Critical |
| **TC-CNL-009** | Dynamic SLA | Boarding 20 Menit pada Menu Berdurasi Masak 25 Menit | Masukkan boarding 20 menit pada menu prep 25 menit | Ditolak: "Dapur membutuhkan waktu penyiapan makanan minimal 25 menit". | Critical |
| **TC-CNL-010** | Dynamic SLA | Pesanan Keranjang Multi-Menu dengan Waktu Masak Variatif| Menu A (10 menit) dan Menu B (22 menit) | Sistem mengambil $\max(10, 22) = 22$ menit sebagai batas toleransi SLA. | Critical |
| **TC-CNL-011** | Cross-Midnight | Penerbangan Dini Hari: Pesan Malam untuk Boarding Besok | Pesan pukul 23:40 WIB, jam boarding 00:45 WIB | Sistem mengenali penambahan hari (+1 hari, selisih 65m), transaksi diterima. | High |
| **TC-CNL-012** | Cross-Midnight | Penerbangan Dini Hari: Selisih Boarding Kurang dari SLA | Pesan pukul 23:55 WIB, jam boarding 00:05 WIB (selisih 10m)| Ditolak: selisih waktu hanya 10 menit menuju boarding esok hari. | High |
| **TC-CNL-013** | Auto-Cancel Dinamis| Sinkronisasi Auto-Cancel Mengikuti Jam Boarding Ketat | Jam boarding 12 menit lagi (dalam toleransi khusus) | Kolom `auto_cancel_at` diset tepat sama dengan `boarding_time` (12m, bukan 15m). | High |
| **TC-CNL-014** | Auto-Cancel Dinamis| Jam Boarding Masih Lama (Contoh: 3 Jam ke Depan) | Boarding 3 jam lagi, timer un-paid default 15 menit | `auto_cancel_at` tetap menggunakan timer 15 menit un-paid agar stok tidak terkunci. | High |
| **TC-CNL-015** | Passive Cancel | Passive Cancel saat Pelanggan Cek Status AJAX | Biarkan pesanan expired, browser polling `/tracking/status` | Respons JSON mengembalikan status `dibatalkan`, record DB terupdate otomatis. | Critical |
| **TC-CNL-016** | Passive Cancel | Passive Cancel saat Mengakses Halaman Struk | Buka URL `/receipt/{order}` pada pesanan yang lewat batas | Controller mengubah status ke `dibatalkan` dan merender status batal. | High |
| **TC-CNL-017** | Passive Cancel | Passive Cancel saat Checkout Transaksi Baru | Customer punya order lama expired, lalu klik checkout baru | Order lama otomatis dibatalkan sebelum order baru divalidasi. | High |
| **TC-CNL-018** | Active Scheduler | Eksekusi Manual Artisan Command Pembatalan | Jalankan perintah `php artisan orders:cancel-expired` | Output console: "Berhasil membatalkan X pesanan kedaluwarsa". | Critical |
| **TC-CNL-019** | Active Scheduler | Scheduler Mengabaikan Pesanan yang Sudah Lunas | Ada order melewati waktu batas tetapi `is_paid = true` | Scheduler tidak membatalkan pesanan tersebut (status tetap `diproses`/`siap`). | Blocker |
| **TC-CNL-020** | Active Scheduler | Scheduler Mengabaikan Pesanan yang Sudah Selesai | Pesanan berstatus `selesai` melewati `auto_cancel_at` | Status tetap `selesai` tanpa ada perubahan. | High |
| **TC-CNL-021** | Pemulihan Stok | Kuota Stok Dipulihkan saat Auto-Cancel Terpicu | Stok awal 8 porsi, pesanan batal 2 porsi | Stok produk di tabel `products` kembali bertambah menjadi 10 porsi. | Critical |
| **TC-CNL-022** | Pemulihan Stok | Pemulihan Stok Multi-Item pada Pembatalan Otomatis | Order berisi 3 jenis menu berbeda kedaluwarsa | Seluruh kuota ketiga menu bertambah kembali sesuai kuantitas masing-masing. | Critical |
| **TC-CNL-023** | Keamanan Bayar | Pencegahan Simulasi Bayar pada Pesanan Kedaluwarsa | Tembak endpoint simulasi bayar saat status `dibatalkan` | Pembayaran ditolak: "Pesanan ini sudah kedaluwarsa atau dibatalkan". | Blocker |
| **TC-CNL-024** | Keamanan Bayar | Simulasi Bayar Berhasil Sebelum Batas Waktu Habis | Eksekusi bayar lunas saat timer tersisa 02:30 | Status pembayaran berubah menjadi `is_paid = true`, status pesanan tetap aman. | High |
| **TC-CNL-025** | Pembatalan Manual | Pembatalan Mandiri oleh Pelanggan Sebelum Bayar | Klik tombol "Batalkan Pesanan" di halaman tracking | Muncul konfirmasi; pesanan berubah menjadi `dibatalkan`, stok kembali pulih. | High |
| **TC-CNL-026** | Pembatalan Manual | Larangan Pembatalan Mandiri jika Sudah Lunas Terbayar | Pelanggan mencoba membatalkan pesanan yang sudah dibayar | Tombol batal disembunyikan; request POST ditolak backend (403/400). | High |
| **TC-CNL-027** | Pembatalan Tenant | Penolakan Pesanan Masuk oleh Tenant (Reject Order) | Tenant klik "Tolak Pesanan" dengan alasan bahan habis | Status berubah menjadi `dibatalkan`, stok sisa menu lain dikembalikan. | High |
| **TC-CNL-028** | Auto-Cancel Cron | Ketahanan Scheduler Cron saat Tidak Ada Pesanan Expired | Jalankan `orders:cancel-expired` saat seluruh order aman | Command selesai tanpa galat runtime (exit code 0). | Medium |
| **TC-CNL-029** | UI State | Banner Informasi Pembatalan di Halaman Tracking | Akses tracking order yang telah dibatalkan otomatis | Banner merah muncul: "Pesanan ini otomatis dibatalkan karena melewati batas waktu". | Medium |
| **TC-CNL-030** | Pembersihan Sesi | Pembersihan `order_code` dari Session Pengguna | Pesanan aktif pengguna telah dinyatakan dibatalkan | Session `order_code` dihapus sehingga pelanggan dapat membuat pesanan baru bersih. | Medium |
| **TC-CNL-031** | Database Log | Audit Trail Perubahan Status Pembatalan Otomatis | Periksa tabel `activity_logs` setelah auto-cancel | Log mencatat event pembatalan otomatis dengan keterangan sistem. | Medium |
| **TC-CNL-032** | Concurrency | Eksekusi Cron Bersamaan dengan Polling Pelanggan | Cron dan request AJAX membatalkan order yang sama persis | Terproteksi database transaction/row lock, tidak terjadi *double stock restore*. | Critical |
| **TC-CNL-033** | Toleransi Jam | Penanganan Perbedaan Zona Waktu Server (WIB UTC+7) | Server mencatat waktu sekarang dalam timezone `Asia/Jakarta` | Kalkulasi jam boarding dan timer 15 menit akurat tanpa selisih offset jam. | High |
| **TC-CNL-034** | Notifikasi Audio | Suara Peringatan Timer Hampir Habis di Halaman Tracking| Timer pembatalan tersisa kurang dari 2 menit | Muncul indikator visual berkedip kuning memperingatkan sisa waktu bayar. | Minor |
| **TC-CNL-035** | Batas Ekstrem | Pesanan dengan Waktu Boarding Tepat 1 Menit Lagi | Simulasi input boarding time selisih 60 detik dari sekarang | Ditolak secara mutlak oleh validasi SLA bandara. | High |

---

### 3.3 Fitur Utama 3: Pemicu (Trigger) "Menuju Tenant" & State Machine Status (30 Kasus Uji)

| ID Kasus Uji | Modul Fitur | Skenario Pengujian | Prosedur Uji / Input Data | Hasil yang Diharapkan (Expected Result) | Severity |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-STA-001** | State Machine | Transisi Sah: `menunggu` $\rightarrow$ `diproses` | Tenant klik tombol "Terima & Masak" pada order lunas | Status berubah menjadi `diproses`, pesanan masuk antrean masak. | High |
| **TC-STA-002** | State Machine | Larangan Masak pada Pesanan yang Belum Dibayar | Tenant mencoba ubah status order un-paid ke `diproses` | Ditolak backend: "Larang perubahan status jika pesanan belum dibayar". | Blocker |
| **TC-STA-003** | State Machine | Transisi Sah: `diproses` $\rightarrow$ `siap` | Tenant klik tombol "Makanan Siap Diserahkan" | Status berubah menjadi `siap`, siap diambil penumpang/porter. | High |
| **TC-STA-004** | SLA Guard | Otomasi Rekam Timestamp `ready_at` via Trigger DB | Periksa kolom `ready_at` saat status berubah ke `siap` | Kolom `ready_at` terisi timestamp terkini via DB trigger `trg_before_order_update_sla_guard`. | High |
| **TC-STA-005** | SLA Guard | Perlindungan Nilai `ready_at` dari Penimpaan Ulang | Update status pesanan lain saat `ready_at` sudah terisi | Nilai `ready_at` awal tetap dipertahankan (tidak tertimpa jam baru). | High |
| **TC-STA-006** | Trigger Aksi | Aktivasi Pemicu "Saya Menuju Tenant" oleh Penumpang | Penumpang menekan tombol "Saya Menuju Tenant" | Request AJAX terkirim, kolom `heading_to_tenant_at` tercatat di database. | Critical |
| **TC-STA-007** | Trigger Aksi | Validasi Waktu Pemicu: Hanya saat Makanan Berstatus `siap` | Penumpang coba tekan tombol saat status masih `menunggu` | Tombol belum muncul/disabled, API menolak trigger sebelum makanan siap. | High |
| **TC-STA-008** | Visual Kasir | Highlight Kartu Pesanan di Dashboard Dapur Tenant | Penumpang telah mengaktifkan pemicu "Menuju Tenant" | Kartu pesanan di layar kasir berkedip hijau / bertuliskan "Penumpang Sedang Berjalan ke Konter". | High |
| **TC-STA-009** | Petunjuk Arah | Tampilan Peta / Lokasi Lantai Konter Tenant | Penumpang menekan tombol menuju tenant | Antarmuka memunculkan teks lokasi konter (cth: "Lantai 1 - Depan Gate 3"). | Medium |
| **TC-STA-010** | State Machine | Transisi Sah: `siap` $\rightarrow$ `selesai` | Kasir menyerahkan bungkusan dan menekan "Pesanan Selesai" | Status berubah menjadi `selesai`. | High |
| **TC-STA-011** | SLA Guard | Otomasi Rekam Timestamp `completed_at` via Trigger DB | Periksa kolom `completed_at` saat status menjadi `selesai` | Kolom `completed_at` otomatis terisi jam sekarang melalui trigger database. | High |
| **TC-STA-012** | State Machine | Penolakan Transisi Ilegal Melompat (Jump Forward) | Tenant coba loncat langsung dari `menunggu` ke `selesai` | Ditolak oleh validator State Machine: "Transisi status tidak diizinkan". | Critical |
| **TC-STA-013** | State Machine | Penolakan Transisi Melompat: `diproses` langsung `selesai` | Tenant melewati tahap `siap` langsung ke `selesai` | Ditolak oleh validator: pesanan harus melalui status `siap` terlebih dahulu. | Critical |
| **TC-STA-014** | State Machine | Penolakan Transisi Ilegal Mundur (Rollback Backward) | Coba ubah status dari `selesai` kembali ke `diproses` | Ditolak oleh backend validator: status pesanan final tidak dapat dimundurkan. | Critical |
| **TC-STA-015** | State Machine | Penolakan Transisi Mundur: `siap` kembali ke `diproses` | Coba ubah status dari `siap` kembali ke `diproses` | Ditolak oleh aturan State Machine satu arah. | High |
| **TC-STA-016** | State Machine | Pesanan Batal Tidak Dapat Diubah Menjadi `diproses` | Tenant mencoba memproses pesanan berstatus `dibatalkan` | Ditolak secara mutlak oleh backend. | Blocker |
| **TC-STA-017** | State Machine | Pesanan Selesai Tidak Dapat Dibatalkan | Tenant atau customer mencoba membatalkan pesanan `selesai` | Ditolak: transaksi telah tuntas diserahterimakan. | Blocker |
| **TC-STA-018** | Alur Porter | Transisi Khusus Layanan Antar Porter | Makanan siap, porter ambil barang menuju lokasi gate | Status tracking pelanggan menampilkan badge "Sedang Diantar Porter". | High |
| **TC-STA-019** | Konfirmasi Serah | Penyerahan Pesanan Porter di Ruang Tunggu Keberangkatan | Porter menyerahkan pesanan kepada penumpang di Gate 3 | Porter/Tenant menandai selesai, pesanan tuntas berstatus `selesai`. | High |
| **TC-STA-020** | Live Polling | Pembaruan Live Status di Layar Ponsel Penumpang | Status diubah tenant di komputer kasir | Halaman tracking di HP penumpang otomatis memperbarui status tanpa reload manual. | High |
| **TC-STA-021** | Audit Trail | Pencatatan Otomatis Log Transisi Status | Periksa tabel `activity_logs` setelah perubahan status | Tercatat log: "Status order #ORD-XXXX diubah dari [diproses] ke [siap]". | Medium |
| **TC-STA-022** | Hak Akses | Larangan Staf Tenant Mengubah Order Tenant Lain | Staf Resto A tembak API update status milik pesanan Resto B | Ditolak dengan kode otorisasi HTTP 403 Forbidden. | Blocker |
| **TC-STA-023** | Hak Akses | Larangan Pengguna Publik Mengakses Endpoint Status Tenant | Pengguna biasa POST ke `/tenant/orders/{id}/status` | Dialihkan ke halaman login atau diblokir 403 Forbidden. | High |
| **TC-STA-024** | CSRF Protection| Proteksi Serangan Pemalsuan Request Lintas Situs (CSRF) | Kirim request POST update status tanpa menyertakan `_token` | Ditolak oleh middleware keamanan Laravel dengan HTTP 419 Page Expired. | High |
| **TC-STA-025** | Tampilan UI | Warna & Label Badge Dinamis Status Pesanan | Verifikasi warna badge di setiap tahapan status | `menunggu` (kuning), `diproses` (oranye/biru), `siap` (biru tua), `selesai` (hijau). | Minor |
| **TC-STA-026** | Filter Tab | Pemisahan Tab Antrean di Layar Pesanan Tenant | Klik tab 'Menunggu', 'Diproses', dan 'Selesai' di kasir | Daftar pesanan terfilter secara presisi sesuai tab yang dipilih. | Medium |
| **TC-STA-027** | SLA Durasi | Perhitungan Durasi Memasak (Lead Time Dapur) | Selisih waktu antara status `diproses` dan `ready_at` | Nilai durasi tersaji di modul analitik untuk pemantauan kepatuhan SLA. | Medium |
| **TC-STA-028** | Double Trigger | Penekanan Tombol "Menuju Tenant" Berulang Kali (Spam) | Penumpang menekan tombol pemicu 5 kali berturut-turut | Sistem mencatat timestamp pertama kali ditekan secara idempoten tanpa error. | Medium |
| **TC-STA-029** | Mode Offline | Respons UI saat Koneksi Internet Penumpang Terputus | Matikan data seluler saat berada di halaman tracking | Muncul indikator "Koneksi terputus, mencoba menghubungkan kembali...". | Medium |
| **TC-STA-030** | Riwayat Order | Sinkronisasi Riwayat Belanja setelah Status `selesai` | Buka menu `/history` dengan memasukkan nomor HP | Pesanan yang baru saja `selesai` langsung tercantum di daftar riwayat belanja. | High |

---

### 3.4 Fitur Pendukung: Validasi Input (BVA/EP), Keamanan, & Anti-Fraud (20 Kasus Uji)

| ID Kasus Uji | Modul Fitur | Skenario Pengujian | Prosedur Uji / Input Data (BVA/EP) | Hasil yang Diharapkan (Expected Result) | Severity |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-SEC-001** | BVA Form | BVA Nomor Telepon: 8 digit angka | Masukkan nomor HP `"08123456"` (8 digit) | Ditolak: "Nomor telepon harus terdiri dari 9 sampai 15 digit angka". | High |
| **TC-SEC-002** | BVA Form | BVA Nomor Telepon: Tepat 9 digit angka (Batas Bawah) | Masukkan nomor HP `"081234567"` (9 digit) | Berhasil lolos validasi input. | High |
| **TC-SEC-003** | BVA Form | BVA Nomor Telepon: Tepat 15 digit angka (Batas Atas) | Masukkan nomor HP `"628123456789012"` (15 digit) | Berhasil lolos validasi input. | High |
| **TC-SEC-004** | BVA Form | BVA Nomor Telepon: 16 digit angka (Melebihi Batas) | Masukkan nomor HP `"6281234567890123"` (16 digit) | Ditolak oleh aturan validasi panjang maksimal 15 digit. | High |
| **TC-SEC-005** | Format Sanitasi| Sanitasi Karakter Non-Angka pada Nomor Telepon | Masukkan nomor HP `"0812-3456-7890"` | Karakter tanda hubung dibersihkan otomatis menjadi `"081234567890"`. | Medium |
| **TC-SEC-006** | Multi-Format | Normalisasi Multi-Format Nomor HP Pelanggan | Riwayat order dicari dengan format `0812...`, `62812...`, `+62812...` | Sistem mampu menyatukan profil pelanggan dan riwayat yang bersangkutan. | High |
| **TC-SEC-007** | BVA Nama | BVA Panjang Karakter Nama: Karakter Kosong / Spasi | Masukkan nama hanya berupa spasi `"   "` | Ditolak: nama pelanggan wajib diisi karakter bermakna. | Medium |
| **TC-SEC-008** | BVA Nama | BVA Karakter Nama Ekstrem Panjang | Masukkan nama sepanjang 101 karakter | Ditolak atau dipotong maksimal 100 karakter sesuai kapasitas tabel. | Medium |
| **TC-SEC-009** | Anti-Fraud | Batas Pesanan Aktif Pelanggan (Active Order Limit) | Akun membuat 5 order `menunggu`, coba buat order ke-6 | Pesanan ke-6 ditolak: "Anda telah mencapai batas maksimal 5 pesanan aktif". | Critical |
| **TC-SEC-010** | Anti-Fraud | Order ke-6 Diizinkan setelah 1 Order Lama Terbayar | Selesaikan pembayaran pada salah satu dari 5 order pending | Kuota pesanan aktif berkurang jadi 4; order baru berhasil dibuat. | High |
| **TC-SEC-011** | Anti-Fraud | Deteksi Pola Spam Pembatalan Pesanan Berulang | Pelanggan membatalkan 5 pesanan dalam rentang 30 menit | Akun otomatis ditandai `is_blocked = true` oleh `FraudDetectionService`. | Critical |
| **TC-SEC-012** | Anti-Fraud | Penolakan Transaksi dari Nomor HP Terblokir | Nomor HP yang berstatus `is_blocked = true` coba checkout | Transaksi langsung ditolak: "Nomor HP Anda sementara diblokir". | High |
| **TC-SEC-013** | Keamanan Data | Pencegahan Serangan SQL Injection pada Form Checkout | Masukkan input `'; DROP TABLE orders; --` pada nama/catatan | Data tersimpan aman via *Prepared Statements PDO*, kueri tidak bocor. | Blocker |
| **TC-SEC-014** | Keamanan Web | Pencegahan Cross-Site Scripting (XSS) pada Catatan Menu | Masukkan `<script>alert('XSS-Test')</script>` di catatan | Karakter di-escape aman (`&lt;script&gt;`) saat ditampilkan di Blade HTML. | Critical |
| **TC-SEC-015** | Keamanan Data | Pencegahan HTML Injection pada Ringkasan Struk | Masukkan tag `<iframe src="malicious.site">` pada nama pembeli | Tag di-escape murni sebagai string teks, frame tidak tereksekusi. | Critical |
| **TC-SEC-016** | Otorisasi | Perlindungan Route Admin dari Pengguna Biasa | Akses langsung `/admin/dashboard` tanpa login kredensial admin | Dialihkan paksa ke halaman login dengan pesan otorisasi (302/403). | High |
| **TC-SEC-017** | Otorisasi | Perlindungan Route Tenant dari Staf Role Lain | User role 'customer' mencoba membuka `/tenant/dashboard` | Akses ditolak dengan kode respon HTTP 403 Forbidden. | High |
| **TC-SEC-018** | Validasi Tiket | Format Kode Penerbangan (Flight Number Validation) | Penumpang masukkan flight number `"GA-312"`, `"JT640"` | Sistem membersihkan spasi dan mengubah otomatis menjadi huruf kapital. | Medium |
| **TC-SEC-019** | Integritas DB | Foreign Key Constraint Integrity pada Hapus Tenant | Administrator mencoba menghapus tenant yang memiliki data pesanan | Basis data MySQL menolak operasi penghapusan (*Restrict on Delete*). | High |
| **TC-SEC-020** | Enkripsi Data | Kerahasiaan Kunci Akses & Variabel Lingkungan (.env) | Periksa apakah berkas konfigurasi rahasia dapat diakses publik | Akses langsung ke `/.env` via browser menghasilkan status 403/404 Forbidden. | Blocker |

---

## 4. KESIMPULAN PEMENUHAN KRITERIA TAHAP 1

Dengan tersusunnya dokumen komprehensif ini, seluruh butir persyaratan pada **Tahap 1 (Mempelajari Sistem dan Menyusun Dokumen Rencana Uji)** telah terpenuhi secara menyeluruh:
1. **Analisis Alur Bisnis FlyDine:** Membedah spesifikasi unik pemesanan bandara, formula kalkulasi biaya (subtotal, diskon, ongkir porter, pajak all-inclusive), SLA jadwal penerbangan, serta pembagian zona keamanan *Airside* vs *Landside*.
2. **Dokumen Test Plan Resmi:** Menetapkan ruang lingkup pengujian, spesifikasi lingkungan uji (*Windows, Linux, PHP 8.2, MySQL 8.0, SQLite*), kriteria lulus uji (*exit criteria* 98% pass & zero blocker), dan jadwal pengujian terstruktur.
3. **Tabel Kasus Uji Lengkap (130 Skenario):** Menghasilkan 130 skenario uji fungsional yang mencakup Fitur 1 (Pemesanan & Kalkulasi), Fitur 2 (Logika Auto-Cancel & Timer Boarding), Fitur 3 (Pemicu "Menuju Tenant" & State Machine), serta Fitur Pendukung (BVA, Anti-Fraud, & Keamanan).
