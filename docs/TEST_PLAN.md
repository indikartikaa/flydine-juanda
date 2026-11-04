# PT ANGKASA PURA INDONESIA (PERSERO)
### Kantor Regional IV, Airport Technology Department, Bandar Udara Juanda, Surabaya

---

# DOKUMEN RENCANA PENGUJIAN (TEST PLAN)
### Rancang Bangun Sistem Pre-Order dan Antrean Digital FlyDine pada Bandar Udara Juanda

| Keterangan | Isi |
| :--- | :--- |
| **Mata kuliah** | Testing dan Implementasi (SIP231107, 3 SKS) |
| **Program studi** | Sistem Informasi |
| **Penyusun** | Masyito Indi Kartika (NPM 24082010172) |
| **Mentor lapangan** | Avies Pitagoras Mukhtar |
| **Periode kegiatan** | 3 Agustus sampai 4 Desember 2026 |
| **Versi dokumen** | 1.0 (final), Oktober 2026 |
| **Catatan** | Kolom Actual Result dan Status pada bab 10 diisi saat pengujian dijalankan (Minggu 17 dan 18). |

---

## Daftar Isi
| Bab | Isi | Bab | Isi |
| :---: | :--- | :---: | :--- |
| **1** | Pendahuluan | **8** | Kriteria masuk dan lulus uji |
| **2** | Ruang lingkup | **9** | Risiko dan mitigasi |
| **3** | Lingkungan dan alat uji | **10** | Skenario pengujian (test case) |
| **4** | Strategi pengujian | **11** | Pengujian otomatis (PHPUnit) |
| **5** | Teknik desain: EP dan BVA | **12** | Defect dan UAT |
| **6** | Jadwal | **13** | Pelaporan dan kesiapan rilis |
| **7** | Peran dan tanggung jawab | | |

---

## 1. Pendahuluan

### 1.1 Tujuan dokumen
Dokumen ini menjelaskan apa yang diuji, bagaimana cara mengujinya, kapan, dan oleh siapa pada aplikasi FlyDine Juanda. FlyDine adalah platform web pre-order makanan di Bandar Udara Juanda yang menghubungkan penumpang (pemesan), tenant (restoran), dan admin (pengelola bandara).

Pengujian dilakukan untuk memastikan empat hal:
1. Fitur berjalan sesuai kebutuhan.
2. Data input divalidasi dengan benar.
3. Sistem tetap stabil saat banyak pemesan checkout bersamaan.
4. Tampilan nyaman dipakai di layar HP.

### 1.2 Cara membaca dokumen ini
Test case adalah satu skenario pengecekan: prasyarat, langkah, dan hasil yang diharapkan (*Expected Result*).
*Expected Result* ditulis dari kebutuhan (PRD dan *use case*), bukan dari apa yang kebetulan dilakukan aplikasi. Jika hasil nyata berbeda, itu temuan (*defect*).

*Actual Result* diisi saat eksekusi dengan apa yang benar-benar terjadi. *Status* adalah kesimpulannya:
| Status | Arti |
| :--- | :--- |
| **PASS** | Actual Result sama dengan Expected Result. |
| **FAIL** | Actual Result berbeda (error, salah hitung, validasi tidak ada). Dicatat sebagai defect. |
| **BLOCKED** | Tes tidak bisa dijalankan karena fitur lain rusak lebih dulu. |
| **N/A** | Fitur belum ada di versi ini. Beri alasan di kolom Actual Result. |

### 1.3 Kaitan dengan silabus
| Sub-materi silabus Testing dan Implementasi | Bagian dokumen | Output |
| :--- | :--- | :--- |
| Siklus penjaminan mutu (STLC) | Bab 4, 6, 8 | Tahapan dan kriteria lulus uji |
| Penyusunan dokumen skenario uji (Test Plan) | Bab 2 sampai 10 | Dokumen ini |
| Black-box testing (BVA dan Equivalence Partitioning, Postman) | Bab 5, 10.9, 10.10 | Test case TC-BVA, TC-EP, TC-API |
| Unit testing dan integration testing | Bab 11 | Skrip uji PHPUnit |
| Web API testing dan load testing (JMeter) | Bab 4, 10.11 | Hasil beban dan batas puncak |
| Defect tracking dan UAT | Bab 12 | Defect log dan lembar persetujuan UAT |
| Dokumen akhir pengujian | Bab 13 | Laporan Hasil Pengujian dan kesiapan rilis MVP |

---

## 2. Ruang Lingkup
Seluruh fitur MVP FlyDine berada dalam lingkup pengujian. Fitur dikelompokkan menjadi delapan modul, ditambah pengujian non-fungsional.

| No | Modul | Yang diuji | Kode ID |
| :---: | :--- | :--- | :---: |
| 1 | Autentikasi dan identitas | Login Tenant Staff dan Admin Ops, pembatasan hak akses (RBAC), profil restoran, identitas customer dari nomor HP (tanpa akun login) | `AUTH` |
| 2 | Manajemen katalog | Tambah, ubah, hapus produk; harga; foto; ketersediaan | `KAT` |
| 3 | Pemesanan (customer) | Katalog, keranjang, checkout, metode ambil dan ongkir, peringatan boarding, pembayaran simulasi, pembatalan | `ORD` |
| 4 | Pemrosesan pesanan (tenant) | Terima atau tolak, siap, selesai, riwayat, isolasi data antar tenant | `PROC` |
| 5 | Pembayaran dan otomasi | Batas waktu bayar, auto-cancel, scheduler | `AUTO` |
| 6 | Komplain pelanggan | Pengajuan komplain dan penanganan oleh admin | `COMP` |
| 7 | Admin dan dashboard | KPI, filter, drill-down, What-If, ekspor PDF dan Excel, manajemen tenant | `ADM` |
| 8 | Fraud monitoring | Blokir otomatis, unblock manual, log aktivitas | `FRD` |
| + | Non-fungsional | Uji beban (JMeter), tampilan HP, lintas browser | `PRF`, `COMPAT` |

**Di luar lingkup:** pembayaran sungguhan (pembayaran disimulasikan), notifikasi otomatis WhatsApp atau push, integrasi sistem bandara lain, uji penetrasi keamanan (dibahas pada dokumen keamanan), dan perangkat iOS fisik.

---

## 3. Lingkungan dan Alat Uji
| Komponen | Keterangan |
| :--- | :--- |
| **Aplikasi** | FlyDine Juanda versi 1.0 (MVP): web Laravel (PHP), Blade, Tailwind CSS, Alpine.js |
| **Basis data** | MySQL (Laragon) untuk pengujian manual dan API. SQLite in-memory untuk tes otomatis, sehingga database asli tidak tersentuh. |
| **Data uji** | Sekitar 200 pesanan uji berkode `DUMMY-` (`DummyOrderSeeder`), akun tenant dan admin uji, nomor HP uji. Dibersihkan setelah pengujian. |
| **Sistem operasi** | Windows 11 |
| **Browser** | Google Chrome, Mozilla Firefox, Microsoft Edge; Chrome DevTools Device Mode untuk tampilan HP |
| **Uji otomatis** | PHPUnit/Pest (`php artisan test`) |
| **Uji API** | Postman |
| **Uji beban** | Apache JMeter |
| **Pencatatan versi** | Versi PHP, Laravel, dan MySQL dicatat saat eksekusi (`php -v`, `php artisan --version`, `SELECT VERSION()`) di Laporan Hasil Pengujian. |

---

## 4. Strategi Pengujian

### 4.1 Tahapan (STLC)
| Tahap | Kegiatan | Hasil |
| :--- | :--- | :--- |
| **1. Analisis kebutuhan** | Mempelajari PRD dan use case | Daftar fitur yang diuji |
| **2. Perencanaan** | Menyusun dokumen ini | Test Plan |
| **3. Desain test case** | Skenario per modul, teknik EP dan BVA | Bab 10 |
| **4. Persiapan lingkungan** | Data uji, akun uji, alat | Lingkungan siap |
| **5. Eksekusi** | Manual, API, otomatis, beban | Actual Result dan Status |
| **6. Pelacakan defect** | Mencatat, memperbaiki, menguji ulang | Defect log |
| **7. UAT dan penutupan** | Uji penerimaan, laporan, kesiapan rilis | Laporan Hasil Pengujian |

### 4.2 Jenis pengujian
| Jenis | Tujuan | Alat | Bagian |
| :--- | :--- | :--- | :--- |
| **Unit dan integrasi otomatis** | Memastikan logika backend benar dan tidak rusak saat kode berubah | PHPUnit/Pest | Bab 11 |
| **Black-box manual** | Mengecek fitur lewat tampilan tanpa melihat kode | Browser | 10.1 sampai 10.8 |
| **Black-box input (EP dan BVA)** | Memastikan validasi data input tepat di batas | Browser, Postman | 10.9 |
| **API** | Mengecek endpoint JSON: status, hak akses, validasi | Postman | 10.10 |
| **Beban** | Menguji ketahanan checkout serentak | JMeter | 10.11 |
| **Kompatibilitas** | Tampilan HP dan antar-browser | DevTools, browser | 10.11 |
| **UAT** | Persetujuan pengguna | Skenario UAT | Bab 12 |

### 4.3 Prinsip yang dipakai
- Modul AUTH dan KAT diuji lebih dulu karena menjadi prasyarat modul lain, agar kegagalan di sana tidak menimbulkan kegagalan palsu di modul berikutnya.
- Pengujian berbasis waktu (auto-cancel, deteksi spam) memakai time mocking (`Carbon::setTestNow`) pada tes otomatis, dan data uji dengan waktu yang diatur pada pengujian manual.
- Fitur khusus MySQL (trigger, stored procedure, fungsi tanggal) diuji di MySQL. Ekspor dan dashboard wajib dicek manual di MySQL karena tes otomatis berjalan di SQLite.
- Data uji berkode `DUMMY-` dibersihkan setelah pengujian; database dibackup sebelum pengujian.

### 4.4 Catatan teknis uji beban
Checkout memakai sesi dan token CSRF, sehingga skrip JMeter harus: menyimpan cookie sesi, mengambil token dari halaman, menambah item ke keranjang, lalu mengirim checkout. Setiap pengguna virtual memakai nomor HP berbeda (CSV Data Set) agar tidak memicu blokir fraud.

---

## 5. Teknik Desain Test Case: EP dan BVA
- **Equivalence Partitioning (EP)** membagi semua kemungkinan nilai input menjadi kelompok (partisi) yang diperlakukan sama oleh sistem. Cukup satu nilai wakil per kelompok yang diuji. Contoh: harga produk minimal Rp100, maka kelompok valid cukup diwakili Rp20.000 dan kelompok tidak valid diwakili -1000.
- **Boundary Value Analysis (BVA)** menguji nilai tepat di batas antar kelompok, karena kesalahan program paling sering terjadi di sana. Polanya: batas minus satu, batas, batas plus satu. Untuk batas Rp100 diuji 99, 100, dan 101.
- Kedua teknik dipakai bersama. EP memilih kelompok yang diuji, BVA menentukan nilai persisnya.

### Aturan bisnis FlyDine yang diuji:
| No | Input | Sumber aturan | Valid | Tidak valid | Nilai uji batas |
| :---: | :--- | :--- | :--- | :--- | :--- |
| 1 | Harga produk | Validasi form produk (minimal Rp100) | 100 ke atas | Kurang dari 100, negatif, bukan angka | 99, 100, 101 |
| 2 | Waktu boarding | Peringatan checkout (30 menit) | 30 menit ke atas | Kurang dari 30 menit (peringatan, checkout tetap boleh) | 29, 30, 31 menit |
| 3 | Pembatalan beruntun | Blokir pada 5 pembatalan dalam 30 menit | 0 sampai 4 | 5 atau lebih | 4, 5, 6; pembatalan tertua di menit ke-31 |
| 4 | Jumlah item per menu | Keranjang | 1 ke atas | 0 | 0, 1, 2 |
| 5 | Nomor penerbangan | Kolom `flight_number` varchar(15) | 1 sampai 15 karakter | Lebih dari 15 | 15, 16 |
| 6 | Nama pemesan | Kolom `customer_name` varchar(100) | 1 sampai 100 karakter | Lebih dari 100 | 100, 101 |
| 7 | Ongkos kirim porter | Validasi numeric dan min:0 | 0 ke atas | Negatif, bukan angka | -1, 0, 1 |
| 8 | Metode pengambilan | Pilihan `ambil_sendiri` atau `diantar` | Dua pilihan itu | Nilai lain | "drone" |

> [!NOTE]
> Nilai ambang (misalnya Rp100) harus dicocokkan dengan validasi di kode sebelum eksekusi. Jika berbeda, nilai uji disesuaikan dan perbedaannya dicatat.

---

## 6. Jadwal Pengujian
| Tahap | Waktu | Keluaran |
| :--- | :--- | :--- |
| **Penyusunan Test Plan awal dan skrip PHPUnit** | 21 sampai 25 September 2026 (Minggu 8) | Test Plan draf, skrip PHPUnit |
| **Penyempurnaan Test Plan dan desain EP/BVA** | Oktober 2026 | Test Plan versi 1.0 (dokumen ini) |
| **Eksekusi manual, API, otomatis, dan JMeter** | 23 sampai 27 November 2026 (Minggu 17) | Actual Result, Status, hasil beban |
| **UAT, perbaikan defect, laporan, kesiapan rilis** | 30 November sampai 4 Desember 2026 (Minggu 18) | Laporan Hasil Pengujian, lembar UAT |

---

## 7. Peran dan Tanggung Jawab
| Peran | Tanggung jawab | Pelaksana |
| :--- | :--- | :--- |
| **Penguji dan pengembang** | Menyusun test plan dan test case, menulis skrip uji, mengeksekusi, memperbaiki defect | Mahasiswa pengembang FlyDine (Masyito Indi Kartika) |
| **Peserta UAT** | Menjalankan skenario UAT dan memberi persetujuan | Mentor lapangan (Avies Pitagoras Mukhtar) dan perwakilan mitra |
| **Penyetuju** | Menyetujui Test Plan dan menilai laporan | Dosen pengampu mata kuliah |

---

## 8. Kriteria Masuk dan Lulus Uji

### Kriteria masuk (siap mulai menguji)
1. Delapan modul sudah selesai dan dapat diakses di lingkungan uji.
2. Data uji, akun uji, dan alat (Postman, JMeter) siap; database sudah dibackup.
3. Skrip PHPUnit tersedia dan dapat dijalankan.

### Kriteria lulus uji (boleh dinyatakan selesai)
1. Test case prioritas tinggi (AUTH, ORD, PROC, AUTO, FRD, dan seluruh TC-BVA/EP terkait checkout) sudah dijalankan dan berstatus PASS, atau defect-nya sudah diperbaiki dan diuji ulang.
2. Tidak ada defect berkeparahan Critical atau High yang masih terbuka.
3. JMeter: tanpa HTTP 5xx, error maksimal 1 persen, rata-rata respons di bawah 3 detik pada 100 pengguna serentak.
4. UAT disetujui mentor (lembar persetujuan) dan Laporan Hasil Pengujian sudah disusun.

### Penghentian sementara
Pengujian dihentikan jika login atau checkout tidak dapat dijalankan sama sekali (*blocker*), sampai defect diperbaiki.

---

## 9. Risiko dan Mitigasi
| Risiko | Mitigasi |
| :--- | :--- |
| Pengujian berbasis waktu (auto-cancel) hasilnya tidak stabil | Time mocking pada tes otomatis; data uji dengan waktu tetap pada uji manual |
| Hasil tes di SQLite tidak mewakili MySQL (fungsi tanggal berbeda) | Ekspor PDF dan Excel, dashboard, dan stored procedure diuji manual di MySQL |
| Data uji tercampur dengan data asli | Kode `DUMMY-`, backup sebelum uji, pembersihan setelah uji (`php artisan dummy:clean`) |
| Uji beban memicu blokir fraud atau mengganggu aplikasi | Nomor HP unik per pengguna virtual; uji di lingkungan lokal atau staging |
| Waktu pengerjaan terbatas | Prioritaskan alur inti: checkout, auto-cancel, status, komplain, fraud |
| Fitur berubah saat pengembangan sehingga test case usang | Tinjau test plan setiap ada perubahan besar dan catat versinya |

---

## 10. Skenario Pengujian (Test Case)
Total 81 test case. Kolom *Actual Result* dan *Status* dikosongkan dan diisi saat eksekusi (Minggu 17). Perbedaan antara hasil nyata dan *Expected Result* dicatat sebagai *defect* (bab 12).

### 10.1 Autentikasi dan identitas (AUTH)
| ID | Skenario | Prasyarat dan langkah | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-AUTH-01** | Login Tenant Staff berhasil | Prasyarat: akun tenant aktif.<br>1. Buka halaman login.<br>2. Isi email dan kata sandi tenant.<br>3. Klik Masuk. | Diarahkan ke Dashboard Tenant. | | |
| **TC-AUTH-02** | Login gagal (kata sandi salah) | 1. Isi email tenant dengan kata sandi salah.<br>2. Klik Masuk. | Muncul pesan gagal dan tetap di halaman login; kejadian tercatat di `activity_logs`. | | |
| **TC-AUTH-03** | Login Admin Ops berhasil | Prasyarat: akun admin_ops.<br>1. Login dengan akun admin. | Diarahkan ke Dashboard Admin. | | |
| **TC-AUTH-04** | Pembatasan hak akses (RBAC) | Prasyarat: login sebagai Tenant Staff.<br>1. Buka alamat `/admin/executive-dashboard`. | Akses ditolak (HTTP 403); halaman admin tidak tampil. | | |
| **TC-AUTH-05** | Identitas customer dari nomor HP | Prasyarat: nomor HP belum pernah dipakai.<br>1. Checkout dengan nomor itu.<br>2. Cek tabel customers.<br>3. Checkout lagi dengan nomor yang sama. | Langkah 2: satu customer baru. Langkah 3: customer yang sama dipakai ulang, tidak ada duplikat. | | |
| **TC-AUTH-06** | Perbarui profil restoran | Prasyarat: login Tenant Staff.<br>1. Buka pengaturan profil.<br>2. Ubah telepon dan nama PIC, unggah logo jpg, simpan.<br>3. Ulangi dengan berkas bukan gambar. | Data dan logo tersimpan dan tampil; berkas bukan gambar ditolak. | | |
| **TC-AUTH-07** | Email PIC duplikat | Prasyarat: email PIC tenant A sudah terdaftar.<br>1. Admin buka Manajemen Tenant, Tambah Mitra.<br>2. Isi email PIC yang sama, simpan. | Ditolak dengan pesan validasi; tenant dan akun tidak dibuat. | | |

### 10.2 Manajemen katalog (KAT)
| ID | Skenario | Prasyarat dan langkah | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-KAT-01** | Tambah produk | Prasyarat: login Tenant Staff.<br>1. Klik Tambah Produk.<br>2. Isi nama, harga 20000, kategori, foto jpg.<br>3. Simpan. | Produk tersimpan dan tampil di daftar tenant dan katalog customer. | | |
| **TC-KAT-02** | Ubah ketersediaan menjadi Habis | Prasyarat: produk A tersedia.<br>1. Edit produk A, ubah status Habis.<br>2. Buka katalog sebagai customer. | Produk A tidak dapat dimasukkan ke keranjang. | | |
| **TC-KAT-03** | Hapus produk | Prasyarat: produk B ada.<br>1. Hapus produk B dan konfirmasi. | Produk B hilang dari daftar tenant dan katalog customer. | | |

### 10.3 Pemesanan customer (ORD)
| ID | Skenario | Prasyarat dan langkah | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-ORD-01** | Keranjang hanya satu tenant | Prasyarat: keranjang berisi menu tenant 1.<br>1. Buka tenant 2.<br>2. Tambah menu ke keranjang. | Sistem mencegah atau memberi peringatan; keranjang tidak bercampur dua tenant. | | |
| **TC-ORD-02** | Total keranjang akurat | 1. Tambah menu A (Rp20.000) dua kali.<br>2. Tekan tombol + dan - di keranjang. | Jumlah dan total (Rp40.000 untuk 2 item) berubah tanpa memuat ulang halaman. | | |
| **TC-ORD-03** | Checkout Ambil Sendiri | 1. Isi data pemesan.<br>2. Pilih Ambil di Counter.<br>3. Pesan. | Pesanan berstatus menunggu, ongkir 0, is_paid 0, auto_cancel_at terisi. | | |
| **TC-ORD-04** | Checkout Diantar Porter | Prasyarat: tenant mengaktifkan layanan antar.<br>1. Pilih Diantar Porter.<br>2. Pilih terminal dan lokasi.<br>3. Pesan. | Total = subtotal + ongkir tenant; lokasi antar tersimpan. Tenant tanpa layanan antar menampilkan Belum Tersedia dan tidak dapat dipilih. | | |
| **TC-ORD-05** | Peringatan boarding | Prasyarat: tipe Penumpang.<br>1. Isi boarding 20 menit dari sekarang. | Peringatan "kurang dari 30 menit, tidak ada refund" tampil; checkout tetap dapat dilanjutkan. | | |
| **TC-ORD-06** | Tenant sedang tutup | Prasyarat: jam tutup tenant sudah lewat.<br>1. Buka katalog. | Tenant berlabel CLOSED, tampil abu-abu, dan tidak dapat dipilih. | | |
| **TC-ORD-07** | Pembayaran simulasi | 1. Buat pesanan dan buka halaman tracking.<br>2. Pilih bayar. | is_paid menjadi 1; pesanan muncul di daftar pesanan tenant. | | |
| **TC-ORD-08** | Batalkan sebelum bayar | Prasyarat: pesanan belum dibayar.<br>1. Di halaman tracking klik Batalkan Pesanan. | Status dibatalkan; pembatalan tercatat untuk deteksi fraud. | | |

### 10.4 Pemrosesan pesanan tenant (PROC)
| ID | Skenario | Prasyarat dan langkah | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-PROC-01** | Terima dan masak | Prasyarat: pesanan lunas berstatus menunggu.<br>1. Tenant klik Terima & Masak. | Status diproses; halaman tracking customer berubah sendiri dalam sekitar 5 detik. | | |
| **TC-PROC-02** | Tolak pesanan | 1. Tenant klik Tolak pada pesanan lunas. | Status dibatalkan; refund diproses semi-manual via WhatsApp oleh staf. | | |
| **TC-PROC-03** | Tandai siap | Prasyarat: status diproses.<br>1. Klik Tandai Siap. | Status siap dan ready_at terisi; tracking menampilkan siap diambil atau diantar. | | |
| **TC-PROC-04** | Selesai | Prasyarat: status siap.<br>1. Verifikasi kode pesanan.<br>2. Klik selesai. | Status selesai dan completed_at terisi; pesanan pindah ke Riwayat. | | |
| **TC-PROC-05** | Isolasi data antar tenant | Prasyarat: login staf tenant B; pesanan milik tenant A.<br>1. Kirim perubahan status pesanan A lewat Postman. | HTTP 403 Unauthorized; status tidak berubah. | | |
| **TC-PROC-06** | Transisi status tidak sah | Prasyarat: pesanan berstatus selesai.<br>1. Kirim perubahan status ke dibatalkan. | Ditolak. Catatan: kode hanya memeriksa daftar status yang diizinkan, sehingga hasil ini diperkirakan menjadi defect. | | |

### 10.5 Pembayaran dan otomasi (AUTO)
| ID | Skenario | Prasyarat dan langkah | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-AUTO-01** | Auto-cancel pesanan belum bayar | Prasyarat: status menunggu, is_paid 0, auto_cancel_at sudah lewat.<br>1. Jalankan `php artisan orders:cancel-expired` (atau tunggu scheduler 1 menit). | Status berubah menjadi dibatalkan. | | |
| **TC-AUTO-02** | Pesanan lunas tidak dibatalkan | Prasyarat: is_paid 1, auto_cancel_at sudah lewat.<br>1. Jalankan perintah yang sama. | Status tetap menunggu. (Riwayat: DEF-001.) | | |
| **TC-AUTO-03** | Batas waktu dinamis | Prasyarat: tipe Penumpang.<br>1. Pesan dengan boarding 10 menit lagi.<br>2. Pesan dengan boarding 2 jam lagi.<br>3. Bandingkan auto_cancel_at. | Pesanan 1: auto_cancel_at sama dengan jam boarding. Pesanan 2: 15 menit setelah dipesan. | | |
| **TC-AUTO-04** | Boarding yang sudah lewat | 1. Isi jam boarding lebih awal dari jam sekarang.<br>2. Pesan. | Ditolak dengan pesan jelas. Catatan: pada uji awal 14 Oktober pesanan diterima dan langsung kedaluwarsa (DEF-002). | | |

### 10.6 Komplain pelanggan (COMP)
| ID | Skenario | Prasyarat dan langkah | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-COMP-01** | Ajukan komplain | Prasyarat: ada pesanan selesai.<br>1. Buka Pusat Bantuan.<br>2. Pilih kategori dan isi keluhan.<br>3. Kirim. | Komplain tersimpan berstatus open dan terhubung ke pesanan. | | |
| **TC-COMP-02** | Komplain pada pesanan belum selesai | Prasyarat: pesanan berstatus diproses.<br>1. Coba ajukan komplain. | Tidak diizinkan. | | |
| **TC-COMP-03** | Admin menangani komplain | 1. Admin buka Komplain Pelanggan.<br>2. Ubah status ke In Progress lalu Resolved. | Status berubah; resolved_at dan penangani tercatat; angka komplain di dashboard ikut berubah. | | |

### 10.7 Admin dan dashboard (ADM)
| ID | Skenario | Prasyarat dan langkah | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-ADM-01** | KPI sesuai data | Prasyarat: data uji.<br>1. Buka Ringkasan Eksekutif.<br>2. Hitung manual volume dan omset lunas satu periode dari tabel orders. | Enam KPI tampil; volume dan omset sama dengan hitungan manual. | | |
| **TC-ADM-02** | Filter tanggal dan tenant | 1. Pilih rentang tanggal dan satu tenant.<br>2. Terapkan. | KPI dan grafik menyesuaikan; periode dan jumlah hari tampil benar. | | |
| **TC-ADM-03** | Export PDF | 1. Klik Export PDF. | File PDF terunduh, angka sama dengan dashboard, laporan muat satu halaman. | | |
| **TC-ADM-04** | Export Excel | 1. Klik Export Excel. | File terunduh dan terbuka di Excel; omset hanya pesanan lunas dan konsisten dengan dashboard. | | |
| **TC-ADM-05** | Drill-down pesanan | 1. Buka Drill-Down Pesanan.<br>2. Klik batang tenant di grafik.<br>3. Klik satu baris pesanan. | Tabel menyempit ke tenant itu; rincian pesanan (item, waktu, SLA) tampil. | | |
| **TC-ADM-06** | What-If kapasitas | 1. Buka Simulasi What-If.<br>2. Naikkan persentase pesanan dan tambah staf. | Utilisasi dan status (AMAN, WASPADA, KRITIS, OVERLOAD) berubah; tenant dengan pesanan valid kurang dari 10 menampilkan "Data belum cukup". | | |
| **TC-ADM-07** | Tangguhkan tenant | 1. Admin tangguhkan tenant.<br>2. Buka katalog customer.<br>3. Aktifkan kembali. | Tenant tidak dapat dipesan saat ditangguhkan dan muncul lagi setelah diaktifkan. | | |
| **TC-ADM-08** | Registrasi tenant baru | 1. Isi form Tambah Mitra (data legalitas kontrak).<br>2. Simpan. | Data tenant tersimpan dan akun login PIC terbentuk otomatis. | | |

### 10.8 Fraud monitoring (FRD)
| ID | Skenario | Prasyarat dan langkah | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-FRD-01** | Blokir otomatis | Prasyarat: satu nomor HP uji punya 5 pesanan dibatalkan dalam 30 menit.<br>1. Lakukan checkout. | Ditolak; is_blocked menjadi 1; tercatat di activity_logs; tampil di Fraud Monitoring. | | |
| **TC-FRD-02** | Customer terblokir checkout | Prasyarat: is_blocked 1.<br>1. Checkout. | Gagal dan kembali ke halaman menu dengan pesan error. | | |
| **TC-FRD-03** | Unblock manual | 1. Admin buka Fraud Monitoring.<br>2. Klik Unblock.<br>3. Customer checkout lagi. | is_blocked kembali 0; checkout berhasil. | | |

### 10.9 Black-box: EP dan BVA
*Nilai batas diuji lewat formulir (atau Postman bila formulir membatasi). Ambang harga dan panjang kolom dicocokkan dengan kode sebelum eksekusi.*

| ID | Input dan nilai uji | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :---: |
| **TC-BVA-01** | Harga produk: 99 | Ditolak, pesan validasi minimal Rp100; produk tidak tersimpan. | | |
| **TC-BVA-02** | Harga produk: 100 | Diterima, produk tersimpan. | | |
| **TC-BVA-03** | Harga produk: 101 | Diterima, produk tersimpan. | | |
| **TC-EP-01** | Harga produk: -1000 (partisi negatif) | Ditolak dengan pesan validasi. | | |
| **TC-EP-02** | Harga produk: "abc" (bukan angka) | Ditolak dengan pesan validasi. | | |
| **TC-BVA-04** | Boarding: 29 menit dari sekarang | Peringatan tampil; checkout tetap boleh. | | |
| **TC-BVA-05** | Boarding: 30 menit dari sekarang (uji di awal menit) | Tanpa peringatan. | | |
| **TC-BVA-06** | Boarding: 31 menit dari sekarang | Tanpa peringatan. | | |
| **TC-EP-03** | Tipe Umum/Staf: tanpa nomor penerbangan dan boarding | Kolom penerbangan tidak tampil; pesanan berhasil dibuat. | | |
| **TC-EP-04** | Tipe Penumpang: waktu boarding kosong | Ditolak; pesan waktu boarding wajib diisi. | | |
| **TC-BVA-07** | Pembatalan beruntun: 4 dalam 30 menit, lalu checkout | Checkout berhasil; akun tidak diblokir. | | |
| **TC-BVA-08** | Pembatalan beruntun: 5 dalam 30 menit, lalu checkout | Ditolak; is_blocked 1; tercatat di activity_logs. | | |
| **TC-BVA-09** | Pembatalan beruntun: 6 dalam 30 menit, lalu checkout | Ditolak; akun diblokir. | | |
| **TC-BVA-10** | Pembatalan beruntun: 5 pembatalan, yang tertua 31 menit lalu | Checkout berhasil (hanya 4 dalam jendela 30 menit). | | |
| **TC-BVA-11** | Jumlah item: kurangi dari 1 menjadi 0 | Item hilang dari keranjang; total tidak dihitung. | | |
| **TC-BVA-12** | Jumlah item: 1 (menu Rp20.000) | Total Rp20.000. | | |
| **TC-BVA-13** | Jumlah item: 2 (menu Rp20.000) | Total Rp40.000. | | |
| **TC-BVA-14** | Nomor penerbangan: 15 karakter | Diterima; pesanan terbuat. | | |
| **TC-BVA-15** | Nomor penerbangan: 16 karakter | Ditolak dengan pesan validasi (bukan error 500). | | |
| **TC-BVA-16** | Nama pemesan: 100 karakter | Diterima; pesanan terbuat. | | |
| **TC-BVA-17** | Nama pemesan: 101 karakter | Ditolak dengan pesan validasi (bukan error 500). | | |
| **TC-BVA-18** | Ongkir porter: -1 | Ditolak dengan pesan validasi. | | |
| **TC-BVA-19** | Ongkir porter: 0 | Diterima; layanan antar aktif dengan tarif Rp0. | | |
| **TC-BVA-20** | Ongkir porter: 1 | Diterima; tarif tersimpan. | | |
| **TC-EP-05** | Metode pengambilan: "drone" (diubah lewat DevTools atau Postman) | Ditolak; pesanan tidak terbuat. | | |

### 10.10 Pengujian API (Postman)
*Endpoint tenant memerlukan sesi login. Di Postman, login lebih dulu agar cookie tersimpan, lalu kirim token CSRF pada header untuk request POST.*

| ID | Skenario | Prasyarat dan langkah | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-API-01** | Lokasi antar Terminal 1 | `GET /api/delivery-locations?terminal=1` | HTTP 200; JSON berisi daftar lokasi Terminal 1 (id dan name). | | |
| **TC-API-02** | Lokasi antar Terminal 2 | `GET /api/delivery-locations?terminal=2` | HTTP 200; JSON berisi daftar lokasi Terminal 2. | | |
| **TC-API-03** | Terminal di luar pilihan | `GET /api/delivery-locations?terminal=3` | Bukan error 500; daftar kosong. | | |
| **TC-API-04** | Parameter terminal kosong | `GET /api/delivery-locations` | Bukan error 500; daftar kosong. | | |
| **TC-API-05** | Status pesanan ada | Prasyarat: pesanan sudah dibuat.<br>`GET /tracking/{kode_pesanan}/status` | HTTP 200; JSON berisi status terbaru. | | |
| **TC-API-06** | Status pesanan tidak ada | `GET /tracking/ORD-TIDAKADA/status` | HTTP 404. | | |
| **TC-API-07** | Tenant ubah status ke siap | Prasyarat: tenant pemilik login; status diproses.<br>`POST /tenant/orders/{id}/status` dengan status=siap | HTTP 200; success true; status berubah; ready_at terisi. | | |
| **TC-API-08** | Status di luar daftar | `POST` dengan status=ditolak | HTTP 422; status tidak berubah. | | |
| **TC-API-09** | Tenant lain mengubah pesanan | Prasyarat: login staf tenant B; pesanan tenant A.<br>`POST /tenant/orders/{id pesanan A}/status` | HTTP 403 Unauthorized. | | |
| **TC-API-10** | Akses tanpa login | Prasyarat: tidak ada sesi.<br>`POST /tenant/orders/{id}/status` | Dialihkan ke halaman login atau HTTP 401; status tidak berubah. | | |

### 10.11 Non-fungsional: beban dan kompatibilitas
| ID | Skenario | Prasyarat dan langkah | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :---: |
| **TC-PRF-01** | Checkout serentak 100 pengguna | Prasyarat: JMeter, 100 nomor HP berbeda, data uji.<br>1. Skrip: buka katalog, tambah ke keranjang, POST checkout dengan token CSRF.<br>2. 100 thread, ramp-up 1 detik.<br>3. Catat Summary Report. | Tanpa HTTP 5xx; error maksimal 1%; rata-rata respons di bawah 3 detik; p95 di bawah 5 detik. | | |
| **TC-PRF-02** | Mencari batas beban | 1. Ulangi dengan 50, 100, 200, dan 300 pengguna.<br>2. Catat titik ketika respons memburuk. | Batas beban nyaman dan penyebab bottleneck tercatat di laporan. | | |
| **TC-COMPAT-01** | Tampilan HP | 1. Chrome DevTools Device Mode lebar 375 px.<br>2. Buka katalog, keranjang, checkout, dan tracking. | Tidak ada scroll horizontal halaman; tidak ada teks terpotong; tombol dapat ditekan. | | |
| **TC-COMPAT-02** | Lintas browser | 1. Jalankan alur memesan di Chrome, Firefox, dan Edge. | Fungsi utama sama di ketiganya. | | |

---

## 11. Pengujian Otomatis (PHPUnit)
Pengujian otomatis memastikan logika backend tetap benar setiap kode berubah. Tes berjalan di SQLite in-memory sehingga database asli tidak tersentuh. Perintah: `php artisan test`.

| Berkas tes | Yang dibuktikan |
| :--- | :--- |
| `DailyEtlReportTest` | ETL harian: agregasi benar, idempoten, data anomali dibuang |
| `CustomerCartAjaxTest` | Keranjang: tambah, kurang, kosongkan (JSON) dan fallback redirect |
| `AnalyticsPagesTest` | Halaman analitik dan ekspor: admin 200, tenant 403, tamu dialihkan |
| `AnalyticsDrillDownTest` | Drill-down: filter, paginasi, validasi parameter (422) |
| `CapacityPlannerTest` | Rumus What-If: kapasitas, utilisasi, batas status, data belum cukup |
| `DummyOrderSeederTest` | Seeder data uji idempoten dan menolak berjalan di production |
| `Tes auto-cancel dan subtotal (Minggu 8)` | Perhitungan subtotal dan pembatalan otomatis berbasis waktu |
| `CancelExpiredOrdersTest` | Empat kasus: belum lunas lewat batas (batal), lunas lewat batas (tetap), belum lewat batas (tetap), tepat di batas (batal) |

*Catatan: Tes `CancelExpiredOrdersTest` telah diimplementasikan di `tests/Feature/CancelExpiredOrdersTest.php` dan diverifikasi dengan hasil 5 passed (17 assertions).*

---

## 12. Defect dan UAT

### 12.1 Siklus defect
Ditemukan, dicatat, dianalisis, diperbaiki, diuji ulang, lalu ditutup (*Closed*). Jika uji ulang gagal, defect dibuka kembali. Defect dicatat di platform kolaborasi tim: [isi nama platform, mis. GitHub Issues atau Trello].

| Keparahan | Arti | Contoh |
| :--- | :--- | :--- |
| **Critical** | Aplikasi tidak dapat dipakai atau data rusak | Checkout selalu error |
| **High** | Fitur utama salah | Pesanan lunas ikut dibatalkan scheduler |
| **Medium** | Fitur sekunder atau validasi kurang | Boarding yang sudah lewat diterima |
| **Low** | Tampilan atau teks | Label terminal salah ketik |

### 12.2 Defect log
| ID | Test case | Deskripsi | Keparahan | Status | Penyelesaian |
| :--- | :--- | :--- | :---: | :---: | :--- |
| **DEF-001** | TC-AUTO-02 | Pesanan lunas (`is_paid` 1) ikut dibatalkan scheduler | High | Closed (14 Okt 2026) | Query diberi kondisi `is_paid = false`. Uji ulang: 3 pesanan belum lunas dibatalkan, 1 pesanan lunas tetap menunggu. |
| **DEF-002** | TC-AUTO-04 | Checkout menerima boarding yang sudah lewat; pesanan langsung kedaluwarsa | Medium | Closed (04 Nov 2026) | Validasi WIB & formula lintas tengah malam (maks 8 jam). Boarding lampau ditolak ('sudah terlewat'), boarding < 15 mnt ditolak (SLA dapur), auto_cancel_at diproteksi selalu di masa depan. |
| **DEF-003** | TC-PROC-06 | Status dapat dimundurkan (selesai ke dibatalkan) lewat API | Medium | Menunggu uji | Usulan: peta transisi status yang diizinkan. |

*Defect baru dari eksekusi Minggu 17 ditambahkan ke tabel ini dengan format yang sama.*

### 12.3 User Acceptance Testing (UAT)
UAT dilakukan bersama mentor lapangan dan, bila memungkinkan, perwakilan tenant pada Minggu 18. Peserta menjalankan skenario berikut tanpa dibantu, lalu menyatakan Diterima atau Ditolak.

| ID | Skenario UAT | Hasil | Catatan |
| :---: | :--- | :---: | :--- |
| **UAT-01** | Customer memesan makanan dari HP sampai pembayaran simulasi | | |
| **UAT-02** | Customer memantau status pesanan sampai siap | | |
| **UAT-03** | Tenant menerima, memproses, dan menyelesaikan pesanan | | |
| **UAT-04** | Tenant mengatur jam operasional dan layanan antar | | |
| **UAT-05** | Admin membaca dashboard dan mengekspor laporan | | |
| **UAT-06** | Admin menangani komplain dan membuka blokir pelanggan | | |

#### Lembar Persetujuan UAT
Dengan ini menyatakan bahwa pengujian penerimaan pengguna FlyDine Juanda telah dilaksanakan dan hasilnya:
- [ ] Disetujui
- [ ] Disetujui dengan catatan
- [ ] Belum disetujui

Surabaya, \_\_\_\_\_\_\_\_\_\_\_\_ 2026

<br><br>

**Avies Pitagoras Mukhtar**  
*Mentor / Pembimbing Lapangan*

---

## 13. Pelaporan dan Kesiapan Rilis

### 13.1 Kerangka Laporan Hasil Pengujian
1. Ringkasan: tujuan, periode, dan kesimpulan satu paragraf.
2. Lingkup dan lingkungan: versi PHP, Laravel, MySQL, alat uji.
3. Hasil manual dan EP/BVA per modul (tabel rekap di bawah) disertai screenshot bukti.
4. Hasil API (Postman), hasil otomatis (jumlah tes lulus, dilewati, gagal).
5. Hasil JMeter: rata-rata respons, p95, error persen, throughput, dan batas beban.
6. Defect: jumlah per keparahan dan status, serta uraian defect yang sudah diperbaiki.
7. Hasil UAT dan kesimpulan: layak rilis, layak dengan catatan, atau belum layak.

### 13.2 Rekap hasil per modul
| Modul | Total | PASS | FAIL | BLOCKED | % lulus |
| :--- | :---: | :---: | :---: | :---: | :---: |
| AUTH | 7 | | | | |
| KAT | 3 | | | | |
| ORD | 8 | | | | |
| PROC | 6 | | | | |
| AUTO | 4 | | | | |
| COMP | 3 | | | | |
| ADM | 8 | | | | |
| FRD | 3 | | | | |
| EP dan BVA | 25 | | | | |
| API | 10 | | | | |
| PRF dan COMPAT | 4 | | | | |
| **Total** | **81** | | | | |

### 13.3 Checklist kesiapan rilis MVP
| Kriteria | Status |
| :--- | :---: |
| Test case prioritas tinggi berstatus PASS | [ ] |
| Tidak ada defect Critical atau High yang terbuka | [ ] |
| Hasil JMeter memenuhi kriteria (5xx nol, error maksimal 1%, rata-rata di bawah 3 detik) | [ ] |
| UAT disetujui mentor | [ ] |
| Data uji berkode DUMMY- dan akun uji dihapus dari database rilis | [ ] |
| Kata sandi bawaan akun tenant diganti dan tidak ada kata sandi di repositori | [ ] |
| Backup database dibuat dan file .env tidak ikut repositori | [ ] |
| Laporan Hasil Pengujian selesai disusun | [ ] |

**Keputusan:**
- [ ] Layak rilis
- [ ] Layak dengan catatan
- [ ] Belum layak

