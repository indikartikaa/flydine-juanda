# TABEL LOG DEFECT & BUG TRACKING (DEFECT REPORTING)
## Sistem FlyDine Juanda International Airport (SUB)
**Versi:** 1.0  
**Tanggal:** 2026-11-04  
**Status Pengujian:** Closed / Ready for MVP Release  

---

## 1. Matriks Klasifikasi Tingkat Keparahan (Severity & Priority)

| Tingkat Keparahan (Severity) | Definisi Dampak Sistem | Prioritas Resolusi |
| :--- | :--- | :--- |
| **S1 - Blocker** | Transaksi macet, server crash, salah kalkulasi uang/stok, atau kebocoran data antar tenant. | P1 - Wajib diperbaiki seketika (Hotfix) |
| **S2 - Critical** | Fitur fungsional utama tidak berjalan sesuai spesifikasi (auto-cancel gagal, trigger macet). | P2 - Harus selesai sebelum rilis MVP |
| **S3 - Major** | Aturan bisnis menyimpang namun sistem masih bisa beroperasi (validasi kurang ketat). | P3 - Diperbaiki pada sprint berjalan |
| **S4 - Minor** | Masalah kosmetik visual UI/UX, typo teks, tata letak responsif kurang presisi. | P4 - Dapat dijadwalkan pada iterasi pemeliharaan |

---

## 2. Tabel Rekapitulasi Defect Log (Bug Tracking Register)

| Defect ID | Modul Terkait | Deskripsi Temuan Bug | Langkah Reproduksi | Severity | Status | Tindakan Resolusi & Hasil Retest |
| :--- | :--- | :--- | :--- | :---: | :---: | :--- |
| **BUG-001** | Airport Zoning | Pesanan antar porter diizinkan menyeberang dari Landside ke Airside melalui manipulasi dropdown lokasi. | 1. Pilih tenant Landside.<br>2. Ubah `delivery_location_id` ke gate Airside.<br>3. Klik Checkout. | **S1 - Blocker** | **CLOSED (Resolved)** | Ditambahkan validasi `isAirside()` dan `isLandside()` pada `CustomerCatalogController::checkout()`. Transaksi kini ditolak dengan peringatan batas keamanan SCP bandara. |
| **BUG-002** | Logika Boarding | Penerbangan dini hari (00:30 WIB) ditolak saat dipesan pukul 23:45 WIB karena dianggap waktu lampau. | 1. Set jam checkout 23:45.<br>2. Masukkan boarding 00:30.<br>3. Submit form. | **S2 - Critical** | **CLOSED (Resolved)** | Diimplementasikan algoritma *Cross-Midnight Detection* (`diffMinutes + 1440`). Jam boarding dini hari otomatis dimajukan +1 hari jika berada dalam rentang 8 jam ke depan. |
| **BUG-003** | Auto-Cancel | Pesanan kedaluwarsa tetap dapat dibayar jika customer membuka URL simulasi pembayaran secara langsung. | 1. Biarkan order melewati `auto_cancel_at`.<br>2. Kirim POST ke `/simulate-payment`. | **S1 - Blocker** | **CLOSED (Resolved)** | Ditambahkan pengecekan `checkAndApplyAutoCancel()` dan validasi ketat status `menunggu` pada `simulatePayment()`. Order batal otomatis ditolak. |
| **BUG-004** | Validasi Form | Nomor telepon dengan karakter tanda hubung (cth: `0812-3456-7890`) gagal validasi regex digit. | 1. Input nomor HP bertanda strip.<br>2. Klik submit checkout. | **S3 - Major** | **CLOSED (Resolved)** | Diterapkan sanitasi `preg_replace('/[^0-9]/', '', $phone)` sebelum kalkulasi panjang digit 9–15. Input nomor HP kini fleksibel dan bersih. |
| **BUG-005** | Anti-Fraud | Customer dapat membuat pesanan 'menunggu' tanpa batas sehingga menguras stok produk tenant tanpa bayar. | 1. Buat 10 order berturut-turut tanpa bayar dari nomor HP yang sama. | **S2 - Critical** | **CLOSED (Resolved)** | Dibuat `OrderSecurityService::checkActiveOrderLimit()` yang membatasi maksimal 5 pesanan aktif berstatus `menunggu`. Pesanan ke-6 otomatis ditolak. |
| **BUG-006** | State Machine | Tenant kasir dapat memproses pesanan yang belum dibayar lunas (`is_paid = false`). | 1. Buka dashboard tenant.<br>2. Klik "Terima & Masak" pada pesanan un-paid. | **S1 - Blocker** | **CLOSED (Resolved)** | Ditambahkan guard `in_array($request->status, ['diproses', 'siap', 'selesai']) && !$order->is_paid` pada `TenantOrderController::updateStatus()`. Ditolak error 422. |
| **BUG-007** | Kuota Stok | Kuota stok produk berkurang ganda jika customer mem-refresh halaman checkout saat jaringan lambat. | 1. Klik tombol checkout.<br>2. Lakukan spam klik / reload cepat. | **S2 - Critical** | **CLOSED (Resolved)** | Seluruh proses pembuatan order dibungkus dalam `DB::transaction()` dengan proteksi ketersediaan stok atomik. |
| **BUG-008** | Struk Digital | Nama varian produk tidak tercetak pada struk jika tenant memperbarui nama menu di kemudian hari. | 1. Pesan produk bervarian.<br>2. Edit nama produk di database.<br>3. Cetak struk lama. | **S3 - Major** | **CLOSED (Resolved)** | Dibuat kolom `product_name_snapshot` pada tabel `order_items` yang menyimpan nama dan varian secara permanen saat checkout. |
| **BUG-009** | UI / Tracking | Timer hitung mundur di halaman tracking berhenti jika pengguna beralih tab peramban (browser blur). | 1. Buka tracking di tab 1.<br>2. Beralih ke tab 2 selama 1 menit.<br>3. Kembali ke tab 1. | **S4 - Minor** | **CLOSED (Resolved)** | Logika JavaScript timer diubah menghitung selisih berbasis target waktu absolut (`auto_cancel_at`) dari jam sistem, bukan interval `setInterval` murni. |
| **BUG-010** | Database Trigger | Kolom `ready_at` tidak terisi jika tenant mengubah status pesanan langsung melalui kueri SQL / batch script. | 1. Jalankan `UPDATE orders SET status = 'siap' WHERE id = 1`. | **S2 - Critical** | **CLOSED (Resolved)** | Dibuat Native MySQL Trigger `trg_before_order_update_sla_guard` yang otomatis mengisi `ready_at = NOW()` di level basis data independen dari PHP. |

---

## 3. Ringkasan Status Resolusi Bug

- **Total Bug Ditemukan:** 10 Defect
- **Resolved & Retested (Lolos Uji Ulang):** 10 Defect (100%)
- **Open Blocker (S1):** 0
- **Open Critical (S2):** 0
- **Open Major (S3):** 0
- **Open Minor (S4):** 0
- **Status Akhir:** **SEMUA CACAT KRITIS TELAH DITUTUP (ZERO DEFECT ON CRITICAL PATH)**

