# PANDUAN BELAJAR & RANGKUMAN PERUBAHAN
## Menu Analitik Operasional (Langkah 0 s.d. Langkah 6) — FlyDine Juanda

Dokumen ini disusun khusus sebagai **panduan belajar terstruktur** bagi mahasiswa yang sedang mempelajari ekosistem **Laravel (PHP), Blade, Tailwind CSS, Alpine.js, dan Chart.js**. Dokumen ini merangkum seluruh evolusi arsitektur, kode nyata, formula matematika, hingga alasan teknis di balik setiap keputusan rekayasa perangkat lunak agar mahasiswa siap mempresentasikan dan mempertahankan proyek ini di hadapan dosen penguji.

---

## DAFTAR ISI

1. [Bagian 1: Gambaran Besar](#bagian-1-gambaran-besar)
2. [Bagian 2: Per Langkah (Langkah 0 sampai 6)](#bagian-2-per-langkah-langkah-0-sampai-6)
   - [Langkah 0: Pengecekan Lingkungan & Keamanan Data](#langkah-0-pengecekan-lingkungan--keamanan-data)
   - [Langkah 1: Penyediaan Data Uji Transaksi & Verifikasi ETL](#langkah-1-penyediaan-data-uji-transaksi--verifikasi-etl)
   - [Langkah 2: Modularisasi Menu Analitik Operasional & Pemecahan Dashboard](#langkah-2-modularisasi-menu-analitik-operasional--pemecahan-dashboard)
   - [Langkah 3: Pembangunan Ulang Halaman Drill-Down Pesanan](#langkah-3-pembangunan-ulang-halaman-drill-down-pesanan)
   - [Langkah 4: Filter Kategori Tenant & Pemetaan Kategori F&B](#langkah-4-filter-kategori-tenant--pemetaan-kategori-fb)
   - [Langkah 5: Pembangunan Halaman Simulasi What-If (Kapasitas & Omset)](#langkah-5-pembangunan-halaman-simulasi-what-if-kapasitas--omset)
   - [Langkah 6: Pemeriksaan Akhir, Audit Keamanan, & Responsivitas Mobile](#langkah-6-pemeriksaan-akhir-audit-keamanan--responsivitas-mobile)
3. [Bagian 3: Penjelasan Khusus (Inti Materi Pembelajaran)](#bagian-3-penjelasan-khusus-inti-materi-pembelajaran)
   - [3A. Analisis Multidimensi & Fitur Drill-Down](#3a-analisis-multidimensi--fitur-drill-down)
   - [3B. Simulasi Kapasitas Tenant (Teori Antrean Operasional)](#3b-simulasi-kapasitas-tenant-teori-antrean-operasional)
   - [3C. Kalkulator Skenario Omset (Legacy vs. Kapasitas Fisik)](#3c-kalkulator-skenario-omset-legacy-vs-kapasitas-fisik)
   - [3D. Arsitektur ETL Harian & Relasinya dengan Dashboard](#3d-arsitektur-etl-harian--relasinya-dengan-dashboard)
   - [3E. Standar Keamanan & Proteksi Sistem](#3e-standar-keamanan--proteksi-sistem)
4. [Bagian 4: Tabel Ketercapaian Silabus Analisis Multidimensi](#bagian-4-tabel-ketercapaian-silabus-analisis-multidimensi)
5. [Bagian 5: Pertanyaan Dosen & Kunci Jawaban Berbasis Kode](#bagian-5-pertanyaan-dosen--kunci-jawaban-berbasis-kode)
6. [Bagian 6: Urutan Rekomendasi Membaca Kode](#bagian-6-urutan-rekomendasi-membaca-kode)
7. [Bagian 7: Latihan Mandiri Mahasiswa](#bagian-7-latihan-mandiri-mahasiswa)
8. [Bagian 8: Analisis Risiko & TODO Pemeliharaan](#bagian-8-analisis-risiko--todo-pemeliharaan)

---

## BAGIAN 1. GAMBARAN BESAR

### 1.1 Ringkasan Perubahan: Dari Monolitik Lambat ke Analitik Modular
Pada kondisi awal proyek, seluruh fitur analitik operasional ditumpuk di dalam satu file template Blade raksasa tunggal ([`resources/views/admin/executive-dashboard.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/executive-dashboard.blade.php)). File ini memuat ringkasan KPI, grafik agregat, pembongkaran tabel transaksi manual yang dibatasi 150 baris, kalkulator proyeksi omset, hingga segmentasi CRM. Akibatnya, query database berjalan sangat lambat karena harus menghitung semua data sekaligus, antarmuka berjejal tanpa hierarki yang jelas, dan halaman tidak dapat merespons penelusuran data bertingkat (*drill-down*) secara lincah. 

Melalui evolusi dari Langkah 0 hingga Langkah 6, sistem ditata ulang menjadi **arsitektur analitik modular**: menu induk *"Analitik Operasional"* diubah menjadi accordion navigasi dengan 4 sub-halaman terisolasi ([`admin.executive-dashboard`](file:///c:/Users/USER/flydine-juanda/routes/web.php#L56), [`admin.analytics.drilldown`](file:///c:/Users/USER/flydine-juanda/routes/web.php#L66), [`admin.analytics.whatif`](file:///c:/Users/USER/flydine-juanda/routes/web.php#L70), dan [`admin.analytics.customers`](file:///c:/Users/USER/flydine-juanda/routes/web.php#L74)). Setiap halaman kini memiliki tanggung jawab spesifik (*Separation of Concerns*), didukung endpoint JSON cepat berbasis AJAX (*Asynchronous JavaScript and XML*), isolasi state filter di URL query string, serta mesin simulasi kapasitas gerai bandara berbasis teori antrean operasional.

### 1.2 Diagram Teks Alur Data (Data Flow Architecture)

Diagram berikut memperlihatkan siklus lengkap aliran data, memperjelas batas antara apa yang dieksekusi di **Sisi Browser (Client/Frontend)** dan apa yang diproses di **Sisi Server (Backend/Database)**:

```text
+========================================================================================================+
|                                    SISI BROWSER (CLIENT - FRONTEND)                                    |
+========================================================================================================+
|  1. PENGGUNA BERINTERAKSI:                                                                             |
|     - Admin klik bar grafik tanggal/tenant di Chart.js, atau geser slider What-If di Alpine.js.        |
|     - Alpine.js memperbarui state internal (misal: date='2026-10-01', orderGrowthPct=25).             |
|                                                                                                        |
|  2. PENGIRIMAN PERMINTAAN (REQUEST):                                                                  |
|     - Skenario A (Navigasi Halaman): Browser meminta dokumen HTML lengkap via HTTP GET.                |
|     - Skenario B (Data Real-Time): fetch() memanggil endpoint JSON latar belakang (GET / POST).       |
+--------------------------------------------------------------------------------------------------------+
                                                   |
                                                   | HTTP Request (GET / POST)
                                                   v
+========================================================================================================+
|                                    SISI SERVER (BACKEND - LARAVEL)                                     |
+========================================================================================================+
|  3. ROUTING & MIDDLEWARE ([routes/web.php]):                                                           |
|     - Route mencocokkan URL (misal: /admin/analytics/drilldown/orders).                                |
|     - Middleware 'auth' memastikan admin sudah login.                                                  |
|     - abort_unless(role === 'admin_ops', 403) memverifikasi hak akses operasional.                    |
|     - Middleware throttle:60,1 mencegah spam request pada simulasi.                                    |
|                                                                                                        |
|  4. CONTROLLER LAYER ([AnalyticsController.php]):                                                      |
|     - Memvalidasi parameter input (start_date, tenant_id, per_page, sort_by).                         |
|     - Meneruskan tugas kalkulasi berat ke Service Class bila diperlukan.                               |
|                                                                                                        |
|  5. SERVICE & DOMAIN LOGIC ([CapacityPlanner.php]):                                                    |
|     - Menghitung beban puncak (lambda_0), waktu saji (S_0), kapasitas (mu), dan utilisasi gerai.       |
|                                                                                                        |
|  6. DATA ACCESS LAYER (Eloquent ORM & Query Builder):                                                  |
|     - Membangun query SQL terparameter (mencegah SQL Injection).                                       |
|     - Eager Loading (with(['tenant', 'orderItems'])) untuk mencegah query berulang N+1.                |
|                                                                                                        |
|  7. BASIS DATA (MySQL / SQLite):                                                                       |
|     - Mengambil data dari tabel orders, order_items, tenants, daily_tenant_summaries.                  |
|     - Mengembalikan kumpulan baris data ke controller.                                                 |
|                                                                                                        |
|  8. FORMATTING & RESPONSE:                                                                             |
|     - Skenario A: Merender view Blade (HTML) yang disisipi CSP Nonce.                                  |
|     - Skenario B: Mengembalikan data terstruktur dalam format JSON (response()->json([...])).          |
+--------------------------------------------------------------------------------------------------------+
                                                   |
                                                   | HTTP Response (HTML / JSON)
                                                   v
+========================================================================================================+
|                                    SISI BROWSER (CLIENT - FRONTEND)                                    |
+========================================================================================================+
|  9. RENDERING AKHIR KE PENGGUNA:                                                                       |
|     - Skenario A: Browser mem-parse HTML Blade, menginisialisasi komponen UI & ikon SVG.               |
|     - Skenario B: fetch().then(json => ...) di Alpine.js menerima JSON:                                |
|       * Mengisi tabel data tanpa kedipan reload halaman.                                               |
|       * Memperbarui visual grafik Chart.js secara instan (.update()).                                  |
|       * Memperbarui indikator status warna (Hijau AMAN / Merah OVERLOAD).                              |
+========================================================================================================+
```

---

## BAGIAN 2. PER LANGKAH (LANGKAH 0 SAMPAI 6)

### Langkah 0: Pengecekan Lingkungan & Keamanan Data
- **a. Tujuan Langkah:** Memastikan lingkungan pengujian otomatis terisolasi secara aman dari database operasional tanpa risiko terhapusnya data riil. Selain itu, langkah ini mengaudit struktur skema tabel `orders` yang ada sebelum penulisan kode dimulai.
- **b. File yang Terlibat:**
  - [`phpunit.xml`](file:///c:/Users/USER/flydine-juanda/phpunit.xml): Konfigurasi pengujian otomatis; dikonfirmasi menggunakan koneksi database in-memory SQLite (`:memory:`) agar operasi `RefreshDatabase` tidak menghapus database MySQL produksi.
  - Skema tabel `orders`: Dikonfirmasi memiliki kolom esensial `ordered_at`, `ready_at`, `completed_at`, `total_amount`, `is_paid`, dan `pickup_method`.
- **c. Route dan Endpoint Baru:** Tidak ada route baru (fase audit awal).
- **d. Alur Kerja Nyata:**
  1. Pengembang menjalankan pemeriksaan konfigurasi environment sebelum memulai penulisan kode.
  2. Sistem membaca file konfigurasi `phpunit.xml` dan memverifikasi variabel `<env name="DB_CONNECTION" value="sqlite"/>`.
  3. Sistem memeriksa isi tabel `orders` untuk memastikan ketiadaan data korup bawaan.
- **e. Potongan Kode Kunci ([`phpunit.xml`](file:///c:/Users/USER/flydine-juanda/phpunit.xml)):**
  ```xml
  <!-- Memastikan pengujian otomatis berjalan di memori RAM, bukan di MySQL lokal -->
  <env name="DB_CONNECTION" value="sqlite"/>
  <env name="DB_DATABASE" value=":memory:"/>
  ```
- **f. Tes Terkait:** Verifikasi bawaan test runner Laravel.
- **g. Cara Mencoba Sendiri:**
  1. Buka terminal di folder proyek.
  2. Jalankan `php artisan test --filter=ExampleTest`.
  3. Amati bahwa tes berjalan di memori tanpa menyentuh database MySQL `flydine_juanda`.

---

### Langkah 1: Penyediaan Data Uji Transaksi & Verifikasi ETL
- **a. Tujuan Langkah:** Menyediakan 200+ data transaksi realistis dengan variasi tenant, waktu saji SLA, dan kasus anomali untuk menguji keandalan dashboard. Data uji dirancang bersifat *idempotent* (dapat dijalankan berulang tanpa merusak data asli) dan dilengkapi verifikasi agregasi ETL harian.
- **b. File yang Terlibat:**
  - [`database/seeders/DummyOrderSeeder.php`](file:///c:/Users/USER/flydine-juanda/database/seeders/DummyOrderSeeder.php): Seeder data transaksi uji lengkap dengan 30 profil penumpang, variasi jam sibuk, dan pembersih otomatis (*cleaner*).
  - [`app/Console/Commands/DailyReportEtl.php`](file:///c:/Users/USER/flydine-juanda/app/Console/Commands/DailyReportEtl.php): Command CLI untuk memproses agregasi harian (Extract, Transform, Load) ke tabel `daily_tenant_summaries`.
  - [`tests/Feature/DummyOrderSeederTest.php`](file:///c:/Users/USER/flydine-juanda/tests/Feature/DummyOrderSeederTest.php): Membuktikan seeder berjalan sukses, idempoten, dan menolak berjalan di mode production.
  - [`tests/Feature/DailyEtlReportTest.php`](file:///c:/Users/USER/flydine-juanda/tests/Feature/DailyEtlReportTest.php): Membuktikan ETL mampu mendeteksi data anomali dan menghitung agregat dengan benar.
- **c. Route dan Endpoint Baru:**
  - Tidak ada route web; fitur dijalankan via artisan: `php artisan db:seed --class=DummyOrderSeeder` dan `php artisan report:etl-daily --from=2026-09-14 --to=2026-10-15`.
- **d. Alur Kerja Nyata:**
  1. Admin menjalankan perintah seeder data dummy di terminal.
  2. Fungsi `DummyOrderSeeder::clean()` mendeteksi dan menghapus pesanan berawalan kode `DUMMY-`.
  3. Seeder menyisipkan 200 pesanan reguler dan 3 pesanan anomali (misal: `ready_at < ordered_at` atau tanpa tenant).
  4. Command ETL dijalankan: pesanan valid diagregasikan ke `daily_tenant_summaries`, sementara 3 pesanan anomali dibuang ke log pembersihan.
- **e. Potongan Kode Kunci ([`database/seeders/DummyOrderSeeder.php#L22-L28`](file:///c:/Users/USER/flydine-juanda/database/seeders/DummyOrderSeeder.php#L22-L28)):**
  ```php
  // Guard keamanan: menolak keras eksekusi jika sistem berada di server produksi nyata
  if (app()->environment('production')) {
      $this->command?->error('ERROR: DummyOrderSeeder tidak diizinkan di production!');
      return;
  }
  // Idempotent: bersihkan data lama berawalan DUMMY- sebelum menyisipkan ulang
  self::clean();
  ```
- **f. Tes Terkait:** [`tests/Feature/DummyOrderSeederTest.php`](file:///c:/Users/USER/flydine-juanda/tests/Feature/DummyOrderSeederTest.php) membuktikan penghapusan bersih data lama dan penolakan pada environment production.
- **g. Cara Mencoba Sendiri:**
  1. Jalankan `php artisan db:seed --class=DummyOrderSeeder`.
  2. Jalankan `php artisan report:etl-daily --from=2026-09-14 --to=2026-10-15`.
  3. Buka MySQL dan pastikan tabel `orders` terisi pesanan berawalan `DUMMY-`.

---

### Langkah 2: Modularisasi Menu Analitik Operasional & Pemecahan Dashboard
- **a. Tujuan Langkah:** Memecah dashboard monolitik menjadi 4 sub-halaman independen dan menata menu sidebar menjadi accordion yang dapat dibuka-tutup. Selain itu, dibuat komponen filter bar bersama agar parameter rentang tanggal dan filter tenant selalu terbawa antar-halaman.
- **b. File yang Terlibat:**
  - [`resources/views/layouts/admin.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/layouts/admin.blade.php): Layout induk admin dengan accordion navigasi Alpine.js dan drawer mobile.
  - [`routes/web.php`](file:///c:/Users/USER/flydine-juanda/routes/web.php#L64-L75): Pendaftaran grup rute `/admin/analytics/*` di bawah pengawalan role `admin_ops`.
  - [`app/Http/Controllers/AnalyticsController.php`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php): Controller baru penanggung jawab halaman analitik terpisah.
  - [`resources/views/components/analytics/filter-bar.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/components/analytics/filter-bar.blade.php): Komponen Blade filter kalender dan tenant yang konsisten di semua halaman.
  - [`resources/views/admin/executive-dashboard.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/executive-dashboard.blade.php): Dashboard utama yang dirampingkan dan dilengkapi 3 kartu tautan navigasi.
  - [`tests/Feature/AnalyticsPagesTest.php`](file:///c:/Users/USER/flydine-juanda/tests/Feature/AnalyticsPagesTest.php): Membuktikan keempat halaman dapat diakses admin, ditolak untuk tenant staff, dan filter terbawa di URL.
- **c. Route dan Endpoint Baru:**
  - `GET /admin/executive-dashboard` (`admin.executive-dashboard`) — Khusus `admin_ops`.
  - `GET /admin/analytics/drilldown` (`admin.analytics.drilldown`) — Khusus `admin_ops`.
  - `GET /admin/analytics/whatif` (`admin.analytics.whatif`) — Khusus `admin_ops`.
  - `GET /admin/analytics/customers` (`admin.analytics.customers`) — Khusus `admin_ops`.
- **d. Alur Kerja Nyata:**
  1. Admin mengklik tombol *"Analitik Operasional"* di sidebar.
  2. State Alpine.js `open = !open` memutar ikon panah 180 derajat dan menurunkan 4 submenu.
  3. Admin memilih *"Ringkasan Eksekutif"* dengan filter rentang tanggal `2026-09-15` s/d `2026-10-15`.
  4. Admin mengklik kartu *"Buka Rincian Pesanan"* di bawah dashboard.
  5. Browser berpindah ke `/admin/analytics/drilldown?start_date=2026-09-15&end_date=2026-10-15` tanpa kehilangan konteks filter.
- **e. Potongan Kode Kunci ([`resources/views/layouts/admin.blade.php#L95-L108`](file:///c:/Users/USER/flydine-juanda/resources/views/layouts/admin.blade.php#L95-L108)):**
  ```blade
  {{-- Alpine.js mengontrol status buka-tutup accordion menu analitik --}}
  <div x-data="{ open: {{ $isAnalyticsActive ? 'true' : 'false' }} }" class="space-y-1">
      <button type="button" @click="open = !open" class="w-full flex items-center justify-between ...">
          <span>Analitik Operasional</span>
          <svg :class="open ? 'rotate-180' : ''" class="transition-transform duration-200 ..."></svg>
      </button>
      <div x-show="open" class="pl-3 space-y-1">
          {{-- Submenu rute analitik --}}
      </div>
  </div>
  ```
- **f. Tes Terkait:** [`tests/Feature/AnalyticsPagesTest.php`](file:///c:/Users/USER/flydine-juanda/tests/Feature/AnalyticsPagesTest.php) membuktikan respon HTTP 200 untuk admin dan 403 Forbidden untuk akun staf tenant.
- **g. Cara Mencoba Sendiri:**
  1. Login ke panel admin di browser.
  2. Klik menu accordion *"Analitik Operasional"* di sidebar kiri.
  3. Klik salah satu submenu dan perhatikan URL browser berpindah dengan mulus.

---

### Langkah 3: Pembangunan Ulang Halaman Drill-Down Pesanan
- **a. Tujuan Langkah:** Membangun antarmuka eksplorasi data mendalam di mana klik pada grafik agregat langsung memfilter tabel transaksi individual. Sistem dilengkapi paginasi dan pengurutan dari sisi server (*server-side*) serta panel laci (*drawer*) untuk memeriksa detail SLA setiap pesanan.
- **b. File yang Terlibat:**
  - [`resources/views/admin/analytics/drilldown.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/drilldown.blade.php): Halaman analitik drill-down dengan Alpine.js dan 6 grafik interaktif Chart.js.
  - [`app/Http/Controllers/AnalyticsController.php#L199-L624`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php#L199-L624): Menyediakan endpoint JSON `drilldownOrders`, `drilldownCharts`, dan `drilldownOrderDetail`.
  - [`database/migrations/proposal_2026_10_16_add_composite_indexes_to_orders_table.php.example`](file:///c:/Users/USER/flydine-juanda/database/migrations/proposal_2026_10_16_add_composite_indexes_to_orders_table.php.example): Dokumen usulan penambahan indeks komposit database guna mencegah *filesort* lambat.
  - [`tests/Feature/AnalyticsDrillDownTest.php`](file:///c:/Users/USER/flydine-juanda/tests/Feature/AnalyticsDrillDownTest.php): Menguji paginasi server, filtering multi-kriteria, dan validasi input.
- **c. Route dan Endpoint Baru:**
  - `GET /admin/analytics/drilldown/orders` (`admin.analytics.drilldown.orders`) — Endpoint JSON tabel transaksi.
  - `GET /admin/analytics/drilldown/charts` (`admin.analytics.drilldown.charts`) — Endpoint JSON 6 dataset grafik.
  - `GET /admin/analytics/drilldown/orders/{order}` (`admin.analytics.drilldown.order-detail`) — Endpoint JSON laci detail pesanan.
- **d. Alur Kerja Nyata:**
  1. Admin membuka halaman Drill-Down dan melihat grafik donat *"Distribusi Status"*.
  2. Admin mengklik irisan donat berwarna merah (*"dibatalkan"*).
  3. Event `onClick` Chart.js menangkap indeks irisan dan mengubah state Alpine `this.status = 'dibatalkan'`.
  4. Alpine.js memicu fungsi `fetchSummaryAndOrders()` yang mengirim permintaan HTTP AJAX ke `/admin/analytics/drilldown/orders?status=dibatalkan`.
  5. Controller memvalidasi parameter, menjalankan query Eloquent terparameter, dan mengembalikan JSON.
  6. Tabel HTML langsung memperbarui baris pesanan tanpa memuat ulang (*refresh*) seluruh halaman web.
- **e. Potongan Kode Kunci ([`app/Http/Controllers/AnalyticsController.php#L275-L281`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php#L275-L281)):**
  ```php
  // Mencegah masalah query lambat N+1 dengan teknik Eager Loading relasi tabel
  $paginated = $orderQuery->with([
      'tenant:id,name,floor_location,zone',
      'orderItems:id,order_id,product_id,product_name_snapshot,quantity,unit_price,subtotal',
      'deliveryLocation:id,name,terminal'
  ])->paginate($perPage);
  ```
- **f. Tes Terkait:** [`tests/Feature/AnalyticsDrillDownTest.php`](file:///c:/Users/USER/flydine-juanda/tests/Feature/AnalyticsDrillDownTest.php) membuktikan endpoint merespon 422 Unprocessable Entity saat diberikan nilai parameter tidak valid (misal status typo).
- **g. Cara Mencoba Sendiri:**
  1. Buka menu *"Drill-Down Pesanan"* di browser.
  2. Klik salah satu batang pada grafik *"Top Mitra Tenant"*.
  3. Amati bahwa tabel di bawahnya langsung menyaring data khusus pesanan tenant yang diklik.

---

### Langkah 4: Filter Kategori Tenant & Pemetaan Kategori F&B
- **a. Tujuan Langkah:** Mengintegrasikan klasifikasi kategori F&B resmi bandara ke dalam seluruh grafik dan tabel drill-down. Mengisi data kolom `tenants.category` yang sebelumnya kosong menggunakan seeder yang idempoten dan terverifikasi.
- **b. File yang Terlibat:**
  - [`database/seeders/UpdateTenantCategoriesSeeder.php`](file:///c:/Users/USER/flydine-juanda/database/seeders/UpdateTenantCategoriesSeeder.php): Seeder pemetaan resmi 26 tenant ke 5 kategori resmi (*Beverages & Coffee*, *Fast Food & Restaurant*, *Bakery & Pastry*, *Snacks & Convenience*, *Indonesian Traditional*).
  - [`app/Http/Controllers/AnalyticsController.php#L473-L490`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php#L473-L490): Menambahkan agregasi volume & omset per kategori F&B ke response JSON grafik.
  - [`resources/views/admin/analytics/drilldown.blade.php#L772-L788`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/drilldown.blade.php#L772-L788): Menambahkan dropdown kategori di filter bar dan chip navigasi hierarki drill-down.
- **c. Route dan Endpoint Baru:**
  - Parameter URL baru `?category=` diintegrasikan pada seluruh endpoint drill-down yang sudah ada.
- **d. Alur Kerja Nyata:**
  1. Admin memilih *"Beverages & Coffee"* pada dropdown filter kategori.
  2. Alpine.js menambahkan parameter `&category=Beverages+%26+Coffee` ke query string URL.
  3. Jalur hierarki chip di atas grafik berubah menjadi: `Semua > 🏷️ Beverages & Coffee`.
  4. Grafik *"Volume & Omset per Kategori"* menyorot batang kopi dengan warna hijau brand `#8dc63f`, sementara grafik tenant hanya menampilkan Starbucks, Killiney, dan gerai kopi lainnya.
- **e. Potongan Kode Kunci ([`database/seeders/UpdateTenantCategoriesSeeder.php#L45-L50`](file:///c:/Users/USER/flydine-juanda/database/seeders/UpdateTenantCategoriesSeeder.php#L45-L50)):**
  ```php
  // Hanya memperbarui baris yang nilainya masih NULL agar tidak menimpa kustomisasi data
  foreach ($mappings as $tenantId => $category) {
      DB::table('tenants')
          ->where('id', $tenantId)
          ->whereNull('category')
          ->update(['category' => $category]);
  }
  ```
- **f. Tes Terkait:** Pengujian di [`tests/Feature/AnalyticsDrillDownTest.php`](file:///c:/Users/USER/flydine-juanda/tests/Feature/AnalyticsDrillDownTest.php) memastikan bahwa filter kategori yang tidak memiliki pesanan menampilkan pesan *empty state* yang rapi.
- **g. Cara Mencoba Sendiri:**
  1. Buka halaman Drill-Down di browser.
  2. Ubah dropdown *"Kategori Tenant"* menjadi *"Bakery & Pastry"*.
  3. Perhatikan daftar menu di tabel hanya menampilkan produk roti seperti Roti O dan Beard Papa's.

---

### Langkah 5: Pembangunan Halaman Simulasi What-If (Kapasitas & Omset)
- **a. Tujuan Langkah:** Membangun kalkulator perencanaan kapasitas gerai berbasis rumus ilmiah teori antrean di class service terisolasi ([`CapacityPlanner`](file:///c:/Users/USER/flydine-juanda/app/Services/CapacityPlanner.php)). Halaman ini menggabungkan simulasi beban fisik gerai dengan kalkulator finansial skenario omset terdahulu.
- **b. File yang Terlibat:**
  - [`app/Services/CapacityPlanner.php`](file:///c:/Users/USER/flydine-juanda/app/Services/CapacityPlanner.php): Service class mandiri penyimpan seluruh formula beban puncak, kapasitas saji, utilisasi, dan staf minimum.
  - [`app/Http/Controllers/AnalyticsController.php#L627-L747`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php#L627-L747): Method `whatif` (tampilan awal) dan `calculateWhatIf` (endpoint API hitung real-time).
  - [`resources/views/admin/analytics/whatif.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/whatif.blade.php): Tampilan simulasi dua kolom (panel kontrol kiri, hasil kartu metrik & tabel kanan).
  - [`tests/Feature/CapacityPlannerTest.php`](file:///c:/Users/USER/flydine-juanda/tests/Feature/CapacityPlannerTest.php): Pengujian akurasi rumus matematika batas ambang status dan formula staf minimum.
- **c. Route dan Endpoint Baru:**
  - `GET /admin/analytics/whatif` (`admin.analytics.whatif`) — Tampilan halaman simulasi.
  - `POST /admin/analytics/whatif/calculate` (`admin.analytics.whatif.calculate`) — Endpoint hitung real-time, dibatasi proteksi `throttle:60,1`.
- **d. Alur Kerja Nyata:**
  1. Admin menggeser slider *"Kenaikan Pesanan"* dari 0% ke +50%.
  2. Alpine.js menahan request selama 300 ms (*debounce*) untuk memastikan slider selesai digeser.
  3. Alpine mengirim payload data via HTTP POST ke `/admin/analytics/whatif/calculate` membawa token keamanan CSRF.
  4. Service `CapacityPlanner` menghitung ulang proyeksi utilisasi seluruh 26 tenant.
  5. JSON diterima browser; grafik batang ganda Chart.js *"Utilisasi Sebelum vs Sesudah"* dan kartu ringkasan langsung terbarui secara instan.
- **e. Potongan Kode Kunci ([`app/Services/CapacityPlanner.php#L46-L58`](file:///c:/Users/USER/flydine-juanda/app/Services/CapacityPlanner.php#L46-L58)):**
  ```php
  // Menghitung jumlah staf minimum agar utilisasi gerai tidak melampaui 80%
  // Formula: utilisasi = (lambda / (staf * 60 / waktu_saji)) * 100 <= 80
  // Disederhanakan menjadi: staf >= (lambda * waktu_saji) / 48
  public static function calculateMinimumStaff(?float $projectedLambda, ?float $serviceMinutes): ?int
  {
      if ($projectedLambda === null || $serviceMinutes === null || $serviceMinutes <= 0) return null;
      if ($projectedLambda <= 0) return 1;
      return max(1, (int) ceil(($projectedLambda * $serviceMinutes) / 48.0));
  }
  ```
- **f. Tes Terkait:** [`tests/Feature/CapacityPlannerTest.php`](file:///c:/Users/USER/flydine-juanda/tests/Feature/CapacityPlannerTest.php) membuktikan kasus utilisasi $>100\%$ terlabeli status `OVERLOAD` dan tenant dengan data $<10$ pesanan berstatus `Data belum cukup`.
- **g. Cara Mencoba Sendiri:**
  1. Buka menu *"Simulasi What-If"* di browser.
  2. Geser slider kenaikan pesanan ke angka `+100%`.
  3. Klik tombol stepper staf menjadi `2`, amati utilisasi gerai langsung turun menjadi aman.

---

### Langkah 6: Pemeriksaan Akhir, Audit Keamanan, & Responsivitas Mobile
- **a. Tujuan Langkah:** Melakukan audit menyeluruh terhadap seluruh komponen antarmuka dan keamanan kode tanpa menambahkan fitur baru. Memastikan tampilan responsif pada layar ponsel 375px, tabel tidak gepeng, dan seluruh 62 unit pengujian otomatis lulus 100%.
- **b. File yang Terlibat:**
  - [`resources/views/layouts/admin.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/layouts/admin.blade.php): Mengatur default off-canvas `-translate-x-full lg:translate-x-0` pada sidebar agar tidak menutupi layar ponsel.
  - [`resources/views/components/analytics/filter-bar.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/components/analytics/filter-bar.blade.php): Penataan grid input tanggal responsif pada layar sempit.
  - [`resources/views/admin/analytics/drilldown.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/drilldown.blade.php): Pemberian lebar minimum `min-w-[880px]` pada tabel dan penyesuaian drawer ponsel.
  - [`resources/views/admin/analytics/whatif.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/whatif.blade.php): Pemberian `min-w-[900px]` pada tabel matriks utilisasi tenant.
- **c. Route dan Endpoint Baru:** Tidak ada route baru (fase audit dan stabilisasi).
- **d. Alur Kerja Nyata:**
  1. Pengguna membuka panel admin melalui browser ponsel (lebar 375px).
  2. Sidebar otomatis tersembunyi di kiri layar; header hanya menampilkan tombol hamburger, judul rapi, dan avatar admin.
  3. Pengguna menekan tombol hamburger; sidebar bergeser mulus ke kanan diiringi latar belakang gelap (*backdrop*).
  4. Pengguna membuka tabel transaksi; tabel tidak berhimpitan gepeng karena dilindungi pembungkus scroll horizontal (`overflow-x-auto`).
- **e. Potongan Kode Kunci ([`resources/views/layouts/admin.blade.php#L27-L28`](file:///c:/Users/USER/flydine-juanda/resources/views/layouts/admin.blade.php#L27-L28)):**
  ```blade
  {{-- Kelas dasar -translate-x-full menyembunyikan sidebar di mobile secara default --}}
  <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-white -translate-x-full lg:translate-x-0 lg:static lg:w-64 transition-transform duration-300"
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
  ```
- **f. Tes Terkait:** Eksekusi seluruh test suite pest/phpunit menghasilkan **62 tests passed, 0 failed**.
- **g. Cara Mencoba Sendiri:**
  1. Buka browser, tekan tombol `F12` untuk membuka Developer Tools.
  2. Aktifkan *Device Toolbar* dan pilih ukuran layar *iPhone SE (375px)*.
  3. Buka halaman analitik, buka-tutup menu hamburger, dan geser tabel ke kanan-kiri.

---

## BAGIAN 3. PENJELASAN KHUSUS (INTI MATERI PEMBELAJARAN)

### 3A. Analisis Multidimensi & Fitur Drill-Down

#### 1. Analogi Sederhana
Bayangkan Anda sedang melihat peta bumi di aplikasi **Google Maps**:
- **Tingkat Makro (Agregat):** Melihat pulau Jawa dari atas hanya terlihat gumpalan hijau dan nama kota Surabaya (seperti melihat total omset Rp 500 juta di dashboard eksekutif).
- **Drill-Down:** Anda memperbesar (*zoom-in*) peta ke arah bandara Juanda, melihat jalan raya, lalu masuk ke Terminal 1 Gate 3, dan akhirnya melihat satu gerai KFC lengkap dengan nomor meja pemesan (seperti menelusuri grafik omset ke satu pesanan spesifik milik penumpang bernama Budi Santoso di Gate 3).

#### 2. Alur Lengkap: Dari Klik Grafik Sampai Drawer Muncul
```text
[Klik Batang Grafik] -> [Event Handler onClick Chart.js] -> [State Alpine Diperbarui]
         -> [fetch() AJAX JSON] -> [Eloquent Query + Eager Loading]
         -> [Update DOM Tabel HTML] -> [Klik Baris Pesanan] -> [Buka Side Drawer]
```
1. **Penyimpanan Filter di URL:** Setiap kali filter berubah (kategori, status, tenant, jam), fungsi Alpine `history.replaceState()` atau navigasi form menyematkan parameter ke URL (contoh: `?status=menunggu&category=Fast+Food+%26+Restaurant`). Hal ini membuat tautan bersifat *shareable* (dapat disalin dan dibagikan ke rekan kerja tanpa kehilangan hasil filter).
2. **Cross-Filtering Antar-Grafik:** Saat pengguna memfilter satu dimensi (misal status pesanan *"menunggu"*), fungsi `fetchCharts()` mengirimkan filter tersebut ke controller. Controller menjalankan query agregat dengan klausul `WHERE status = 'menunggu'` untuk kelima grafik lainnya. Hasilnya, grafik jam pesan dan grafik top tenant otomatis menyesuaikan diri hanya menampilkan data pesanan yang berstatus menunggu.
3. **Mengapa Paginasi & Pengurutan Dilakukan di Server (Server-Side)?**
   Jika bandara memiliki 50.000 riwayat transaksi sebulan, mengirimkan seluruh 50.000 data JSON ke browser akan membuat memori ponsel pengguna hang (membengkak puluhan megabyte). Dengan paginasi server via method `paginate(10)`, database MySQL hanya mengambil 10 baris data yang sedang dilihat menggunakan klausa SQL `LIMIT 10 OFFSET 0`.
4. **Mencegah Masalah Query N+1 dengan Eager Loading:**
   *Masalah N+1:* Jika kita mengambil 10 pesanan lalu memanggil `$order->tenant->name` di dalam perulangan (*loop*), Laravel secara default akan menjalankan 1 query untuk mengambil pesanan, ditambah 10 query terpisah ke tabel tenants (total 11 query).
   *Solusi Eager Loading:* Dengan menuliskan `Order::with(['tenant', 'orderItems'])`, Laravel hanya menjalankan tepat 2 query menggunakan klausa `WHERE tenant_id IN (1, 2, 8, ...)`. Waktu eksekusi database terpangkas dari ratusan milidetik menjadi hitungan belasan milidetik.
5. **Proposal Indeks Komposit Database:**
   Pada file proposal [`database/migrations/proposal_2026_10_16_add_composite_indexes_to_orders_table.php.example`](file:///c:/Users/USER/flydine-juanda/database/migrations/proposal_2026_10_16_add_composite_indexes_to_orders_table.php.example), diusulkan penambahan indeks komposit:
   - `idx_orders_tenant_ordered` pada kolom `(tenant_id, ordered_at)`: Mengikuti aturan B-Tree *Leftmost Prefix*. Ketika admin menyaring tenant tertentu dan mengurutkan dari transaksi terbaru (`ORDER BY ordered_at DESC`), MySQL langsung membaca indeks terurut tanpa perlu melakukan pemilahan memori yang lambat (*filesort*).

---

### 3B. Simulasi Kapasitas Tenant (Teori Antrean Operasional)

Di lingkungan bandara, keterlambatan penyajian makanan berdampak fatal karena penumpang terikat oleh batas waktu panggilan masuk pesawat (*boarding time*). Jika antrean gerai F&B macet, penumpang akan membatalkan pesanan secara massal demi mengejar pesawat.

Class service [`app/Services/CapacityPlanner.php`](file:///c:/Users/USER/flydine-juanda/app/Services/CapacityPlanner.php) menerapkan formula kapasitas operasional yang terstandarisasi:

#### 1. Rincian Formula Matematika
1. **Beban Puncak Historis ($\lambda_0$ / Lambda Nol):**
   Rata-rata kedatangan pesanan per jam pada jam tersibuk gerai:
   $$\lambda_0 = \frac{\text{Jumlah Pesanan pada Jam Tersibuk Non-Batal}}{\text{Jumlah Hari dalam Rentang Periode}}$$
2. **Waktu Saji Historis ($S_0$ / Service Time dalam Menit):**
   Rata-rata durasi penyelesaian pesanan yang sah ($\text{ready\_at} \ge \text{ordered\_at}$):
   $$S_0 = \frac{\sum (\text{ready\_at} - \text{ordered\_at})}{\text{Jumlah Pesanan Valid}}$$
3. **Kapasitas Saji Maksimal per Jam ($\mu$ / Mu):**
   Jumlah pesanan maksimal yang sanggup diselesaikan gerai dalam tempo 1 jam (60 menit):
   $$\mu = \frac{\text{Jumlah Staf} \times 60}{S}$$
   *(Jika pengguna tidak mengisi target waktu saji, nilai $S$ otomatis menggunakan $S_0$).*
4. **Tingkat Utilisasi Gerai ($\rho$ / Rho dalam Persen):**
   Rasio antara pesanan yang datang terhadap daya tampung maksimal gerai:
   $$\text{Utilisasi} = \left( \frac{\lambda_{\text{proyeksi}}}{\mu} \right) \times 100\%$$
   di mana $\lambda_{\text{proyeksi}} = \lambda_0 \times (1 + \frac{\%\text{Kenaikan Pesanan}}{100})$.
5. **Kategori Ambang Status Utilisasi:**
   - $\rho < 70\%$ : **AMAN** (Gerai beroperasi lancar, antrean terkendali).
   - $70\% \le \rho \le 90\%$ : **WASPADA** (Antrean mulai memanjang saat jam sibuk).
   - $90\% < \rho \le 100\%$ : **KRITIS** (Kapasitas hampir habis, rawan melanggar batas 15 menit).
   - $\rho > 100\%$ : **OVERLOAD** (Gerai kelebihan beban; pesanan dipastikan menumpuk dan memicu pembatalan).
6. **Jumlah Staf Minimum (Rekomendasi Manajerial):**
   Dihitung agar beban utilisasi tidak melampaui batas aman $80\%$ ($\rho \le 80$):
   $$\frac{\lambda \times 100}{\frac{\text{staf} \times 60}{S}} \le 80 \implies \text{Staf} \ge \left\lceil \frac{\lambda \times S}{48} \right\rceil$$

#### 2. Contoh Perhitungan Nyata Step-by-Step
Misalkan pada tenant **KFC Terminal 1** selama periode 12 hari:
- Pada jam 12:00 (jam tersibuk), tercatat ada 12 pesanan non-batal $\implies \lambda_0 = \frac{12}{12} = 1.0\text{ pesanan/jam}$.
- Rata-rata waktu saji historis $S_0 = 10.0\text{ menit}$.
- Simulasi skenario lonjakan liburan: Kenaikan pesanan $+25\%$, dialokasikan $2\text{ staf}$:
  1. $\lambda_{\text{proyeksi}} = 1.0 \times (1 + 0.25) = 1.25\text{ pesanan/jam}$.
  2. Kapasitas baru $\mu = \frac{2 \times 60}{10.0} = 12.0\text{ pesanan/jam}$.
  3. Utilisasi baru $\rho = \left( \frac{1.25}{12.0} \right) \times 100\% = 10.4\%$.
  4. Status: $10.4\% < 70\% \implies$ **AMAN**.
  5. Staf minimum: $\lceil \frac{1.25 \times 10.0}{48} \rceil = \lceil 0.26 \rceil = 1\text{ staf}$.

#### 3. Mengapa Ada Kasus *"Data Belum Cukup"*?
Jika gerai memiliki pesanan valid kurang dari 10 transaksi atau belum memiliki riwayat `ready_at` sama sekali, sistem secara tegas menetapkan status *"Data belum cukup"* ([`CapacityPlanner.php#L147`](file:///c:/Users/USER/flydine-juanda/app/Services/CapacityPlanner.php#L147)). Ini adalah prinsip etika data (*data integrity*): **sistem tidak boleh mengarang angka rata-rata waktu saji dari sampel yang terlalu kecil**, karena akan menghasilkan kesimpulan yang menyesatkan bagi pengambil keputusan.

#### 4. Keterbatasan Model Kapasitas Ini
- Mengasumsikan laju kedatangan bersifat deterministik/seragam dalam 1 jam (belum memperhitungkan kedatangan acak gelombang *Poisson Arrival*).
- Mengasumsikan setiap jenis menu membutuhkan waktu masak yang sama (padahal ayam goreng cepat saji berbeda durasi masaknya dengan makanan yang dimasak dari nol).

---

### 3C. Kalkulator Skenario Omset (Legacy vs. Kapasitas Fisik)

Halaman What-If memiliki bagian kedua di bawahnya, yaitu **Kalkulator Skenario Finansial Omset** ([`whatif.blade.php#L900-L1150`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/whatif.blade.php#L900-L1150)):
- **Perbedaan Filosofis:**
  - *What-If Kapasitas (Bagian 1):* Berbasis fisika operasional dapur (kecepatan tangan staf, menit penyajian, dan jumlah kompor).
  - *Skenario Omset (Bagian 2):* Berbasis makro-finansial elastisitas bisnis bandara (omset acuan, nilai belanja rata-rata AOV, dan trafik penumpang).
- **Asumsi Konstanta Tetap:**
  Model finansial ini menggunakan konstanta terkalibrasi khusus bandara:
  - *Conversion Rate (Tingkat Konversi Pengunjung):* Dipatok $40\%$ (dari penumpang yang lewat di depan gerai, sekitar 40% memutuskan memesan makanan).
  - *Risiko Pembatalan Skenario Buruk:* Dipatok $14.5\%$ omset hangus akibat penumpang panik meninggalkan gerai saat terjadi *bottleneck* antrean mendekati waktu boarding.
  - Mahasiswa harus menjelaskan kepada dosen bahwa angka-angka ini adalah **asumsi model estimasi cepat** (*heuristic model*) untuk perencanaan anggaran.

---

### 3D. Arsitektur ETL Harian & Relasinya dengan Dashboard

ETL (*Extract, Transform, Load*) adalah pipa pemrosesan data otomatis di balik layar:

```text
[Tabel orders (Data Transaksi Mentah)] 
         |
         v (Dijalankan tiap malam via Scheduler / Cron Job)
[php artisan report:etl-daily]
  1. Extract   : Mengambil pesanan kemarin berdasarkan ordered_at WIB.
  2. Transform : Membersihkan data anomali (buang tanpa tenant, validasi ready_at >= ordered_at).
  3. Load      : Melakukan UPSERT ke tabel daily_tenant_summaries.
         |
         +---------------------------------------+
         |                                       |
         v                                       v
[Dashboard Eksekutif]                   [Drill-Down Pesanan]
Membaca: daily_tenant_summaries         Membaca: tabel orders langsung
Alasan : Query sangat cepat karena      Alasan : Butuh rincian detail per
         data sudah dihitung per hari.           baris transaksi dan nama item.
```

- **Idempotensi ETL:** Perintah ETL dapat dijalankan 10 kali untuk tanggal yang sama tanpa menghasilkan data ganda, karena menggunakan operasi database `UPSERT` (`updateOrInsert`) dengan kunci unik `(summary_date, tenant_id)`.

---

### 3E. Standar Keamanan & Proteksi Sistem

1. **Role-Based Access Control (RBAC):**
   Di setiap method controller analitik, terpasang pengawal baris pertama:
   ```php
   abort_unless(auth()->user()->role === 'admin_ops', 403);
   ```
   Bila akun staf gerai (`tenant_staff`) atau pelanggan mencoba mengetik URL analitik di browser, server langsung menghentikan proses dan membalas dengan kode status **403 Forbidden**.
2. **Validasi Parameter Ketat:**
   Seluruh input dari browser (seperti `status`, `sla`, `per_page`, `sort_by`) divalidasi menggunakan aturan Laravel `in:...` ([`AnalyticsController.php#L220-L227`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php#L220-L227)). Pengguna tidak dapat menyuntikkan nama kolom acak di luar daftar putih (*whitelist*).
3. **Pencegahan SQL Injection:**
   Tidak ada satupun kode yang menyambung string input pengguna secara mentah (`raw query string concatenation`). Seluruh filtering menggunakan parameter binding PDO (`?`) bawaan Eloquent.
4. **Penerapan CSP Nonce (Content Security Policy):**
   Untuk mencegah serangan injeksi skrip jahat (*Cross-Site Scripting / XSS*), browser melarang eksekusi skrip inline sembarangan. Middleware [`SecurityHeaders.php`](file:///c:/Users/USER/flydine-juanda/app/Http/Middleware/SecurityHeaders.php) membuat token unik acak kriptografis (Nonce) untuk setiap request web. Seluruh tag `<script>` di Blade wajib menyematkan atribut `nonce="{{ $cspNonce ?? '' }}"`.

---

## BAGIAN 4. TABEL KETERCAPAIAN SILABUS "ANALISIS MULTIDIMENSI & FITUR DRILL-DOWN"

| No | Klausul Persyaratan Silabus | Status Ketercapaian | Bukti File & Letak Implementasi |
|:---|:---|:---:|:---|
| 1 | **Dimensi Waktu Lengkap (Tanggal & Jam)** | **Terpenuhi** | [`AnalyticsController.php#L372-L408`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php#L372-L408) (Grafik harian & breakdown 24 jam). |
| 2 | **Dimensi Entitas Bisnis (Tenant & Kategori F&B)** | **Terpenuhi** | [`UpdateTenantCategoriesSeeder.php`](file:///c:/Users/USER/flydine-juanda/database/seeders/UpdateTenantCategoriesSeeder.php) dan [`AnalyticsController.php#L442-L490`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php#L442-L490). |
| 3 | **Dimensi Operasional & SLA Waktu Saji** | **Terpenuhi** | Filter status (`menunggu`, `diproses`, dll) & kepatuhan SLA $\le 15$ menit di [`AnalyticsController.php#L90-L105`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php#L90-L105). |
| 4 | **Dimensi Logistik Bandara (Metode Ambil & Gate)** | **Terpenuhi** | Filter `pickup_method` (ambil sendiri vs diantar) dan pencarian nomor gate di [`drilldown.blade.php#L847-L886`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/drilldown.blade.php#L847-L886). |
| 5 | **Interaktivitas Visual Klik pada Grafik (Cross-Filter)** | **Terpenuhi** | Event handler `onClick` pada 6 grafik Chart.js di [`drilldown.blade.php#L339-L625`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/drilldown.blade.php#L339-L625). |
| 6 | **Jalur Penelusuran Hierarkis (Chip Breadcrumb)** | **Terpenuhi** | Indikator `Semua > Kategori > Tenant > Tanggal` di [`drilldown.blade.php#L890-L935`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/drilldown.blade.php#L890-L935). |
| 7 | **Transisi Metrik Agregat ke Baris Mikro (Granular Table)** | **Terpenuhi** | Endpoint paginasi server `drilldownOrders` di [`AnalyticsController.php#L199-L336`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php#L199-L336). |
| 8 | **Inspeksi Rincian Pesanan Mendalam (Side Drawer)** | **Terpenuhi** | Drawer panel samping dengan linimasa SLA meter di [`drilldown.blade.php#L1512-L1650`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/drilldown.blade.php#L1512-L1650). |
| 9 | **Optimasi Skalabilitas & Anti-N+1 Query** | **Terpenuhi** | Eager loading `with(...)` di controller & proposal indeks komposit di [`proposal_..._add_composite_indexes_to_orders_table.php.example`](file:///c:/Users/USER/flydine-juanda/database/migrations/proposal_2026_10_16_add_composite_indexes_to_orders_table.php.example). |
| 10 | **Simulasi Perencanaan Beban (What-If Capacity Planning)** | **Terpenuhi** | Service class matematika antrean di [`CapacityPlanner.php`](file:///c:/Users/USER/flydine-juanda/app/Services/CapacityPlanner.php) & UI real-time di [`whatif.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/whatif.blade.php). |

---

## BAGIAN 5. PERTANYAAN DOSEN & KUNCI JAWABAN BERBASIS KODE

### Q1 (Pertanyaan Jebakan Teori): "Mengapa kalkulasi simulasi What-If dihitung di server melalui AJAX POST, bukan langsung dihitung di JavaScript browser saja?"
**Jawaban Mahasiswa:**
> *"Perhitungan di sisi server via class [`CapacityPlanner.php`](file:///c:/Users/USER/flydine-juanda/app/Services/CapacityPlanner.php) dipilih demi menjaga **Single Source of Truth** (satu sumber kebenaran rumus). Jika logika rumus diletakkan di JavaScript browser, saat dosen atau pihak auditor meminta ekspor dokumen resmi PDF via backend ([`ExecutiveDashboardController::exportSimulationPdf`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/ExecutiveDashboardController.php)), kita terpaksa menulis ulang rumus yang sama di PHP. Hal itu melanggar prinsip DRY (*Don't Repeat Yourself*) dan berisiko menimbulkan selisih angka antara apa yang terlihat di layar dan apa yang tercetak di kertas PDF. Selain itu, dengan kalkulasi di server, rumus matematika dapat diuji secara otomatis menggunakan PHPUnit Test suite ([`CapacityPlannerTest.php`](file:///c:/Users/USER/flydine-juanda/tests/Feature/CapacityPlannerTest.php))."*

### Q2: "Mengapa grafik di Dashboard Eksekutif membaca tabel `daily_tenant_summaries`, sementara halaman Drill-Down membaca tabel `orders`?"
**Jawaban Mahasiswa:**
> *"Ini adalah penerapan pemisahan beban query (OLAP vs. OLTP). Dashboard Eksekutif berfokus pada kecepatan muat data historis skala besar, sehingga membaca tabel hasil pra-agregasi ETL harian (`daily_tenant_summaries`) yang sangat ringan. Sebaliknya, halaman Drill-Down membutuhkan rincian nomor penerbangan, nama menu belanjaan, dan waktu menit penyajian individual, sehingga wajib membaca tabel relasional transaksi mentah (`orders` dan `order_items`)."*

### Q3: "Bagaimana cara mencegah terjadinya masalah N+1 Query pada tabel daftar transaksi?"
**Jawaban Mahasiswa:**
> *"Menggunakan teknik **Eager Loading** pada Eloquent ORM. Di [`AnalyticsController.php` baris 276-280](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php#L276-L280), kita memanggil method `with(['tenant', 'orderItems', 'deliveryLocation'])`. Laravel akan mengambil data transaksi, lalu mengeksekusi satu query `WHERE IN` untuk mengambil seluruh relasi tenant dan order item sekaligus, bukan mengeksekusi query satu per satu di setiap baris perulangan."*

### Q4: "Mengapa Anda mengusulkan indeks gabungan (composite index) pada kolom `(tenant_id, ordered_at)` di tabel orders?"
**Jawaban Mahasiswa:**
> *"Karena tabel `orders` sebelumnya hanya memiliki indeks komposit pada `(tenant_id, status, ordered_at)`. Berdasarkan prinsip kerja struktur data B-Tree (aturan *Leftmost Prefix*), jika admin melakukan drill-down pada gerai tenant tertentu untuk melihat seluruh transaksi tanpa memfilter status, keberadaan kolom `status` di tengah indeks lama memutus pembacaan urutan `ordered_at`. Akibatnya MySQL terpaksa melakukan operasi *Using filesort* di memori. Indeks `(tenant_id, ordered_at)` memungkinkan MySQL langsung membaca rentang waktu pesanan secara terurut tanpa proses sorting tambahan."*

### Q5: "Mengapa pada perhitungan SLA, Anda mensyaratkan `ready_at >= ordered_at`?"
**Jawaban Mahasiswa:**
> *"Untuk membersihkan data anomali atau data kotor (*data hygiene*). Secara logika fisika, makanan tidak mungkin siap disajikan sebelum pesanan tersebut dibuat oleh pelanggan. Jika ada bug sistem kasir yang menyebabkan timestamp jam siap lebih kecil dari jam pesan, selisih waktu akan bernilai negatif dan merusak nilai rata-rata SLA bandara. Data anomali semacam ini diabaikan dari perhitungan rata-rata di [`AnalyticsController.php` baris 94](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php#L94). "*

### Q6: "Apa yang dimaksud dengan sifat idempoten (*idempotent*) pada seeder dan command ETL Anda?"
**Jawaban Mahasiswa:**
> *"Idempoten berarti sebuah operasi dapat dieksekusi berkali-kali namun menghasilkan kondisi akhir yang sama persis tanpa memicu duplikasi data atau error. Pada [`DummyOrderSeeder.php`](file:///c:/Users/USER/flydine-juanda/database/seeders/DummyOrderSeeder.php), seeder selalu memanggil method `clean()` untuk membersihkan pesanan dummy lama sebelum membuat pesanan baru. Pada [`DailyReportEtl.php`](file:///c:/Users/USER/flydine-juanda/app/Console/Commands/DailyReportEtl.php), pemuatan data menggunakan klausa `updateOrInsert` berdasarkan kunci unik `(summary_date, tenant_id)`."*

### Q7: "Bagaimana cara kerja cross-filter antar-grafik Chart.js tanpa me-refresh browser?"
**Jawaban Mahasiswa:**
> *"Setiap instance Chart.js memiliki opsi konfigurasi `onClick: (event, elements) => { ... }`. Saat elemen grafik diklik (misal bar jam 14:00), fungsi JavaScript mengambil data jam tersebut lalu mengubah variabel reaktif Alpine.js `this.hour = 14`. Perubahan state ini memicu method `this.applyFilter()` yang memanggil endpoint JSON `/admin/analytics/drilldown/charts` via `fetch()`. Setelah data JSON baru diterima, seluruh grafik lainnya diperbarui menggunakan method Chart.js `.update()`."*

### Q8: "Mengapa proyek ini menggunakan Alpine.js alih-alih framework besar seperti React atau Vue?"
**Jawaban Mahasiswa:**
> *"Alpine.js dirancang khusus untuk arsitektur server-side rendering seperti Laravel Blade. Ukuran pustaka Alpine sangat ringkas (kurang dari 15 KB), tidak memerlukan konfigurasi kompilasi yang rumit, dan dapat disematkan langsung di atribut tag HTML (seperti `x-data`, `@click`, `x-show`). Ini memberikan interaktivitas modern yang cepat tanpa membebani performa browser."*

### Q9: "Mengapa tag script inline di view Blade harus menggunakan atribut `nonce`?"
**Jawaban Mahasiswa:**
> *"Untuk mematuhi kebijakan keamanan web modern **CSP (Content Security Policy)** yang dikelola di [`SecurityHeaders.php`](file:///c:/Users/USER/flydine-juanda/app/Http/Middleware/SecurityHeaders.php). Nonce adalah string acak kriptografis yang berubah setiap kali halaman di-refresh. Browser hanya akan mengeksekusi tag skrip yang memiliki nilai nonce yang cocok dengan header HTTP. Hal ini melindungi panel admin dari serangan injeksi skrip jahat pihak ketiga (*Stored XSS*)."*

### Q10 (Pertanyaan Jebakan Kapasitas): "Apa batasan paling signifikan dari model kapasitas yang diterapkan pada class CapacityPlanner?"
**Jawaban Mahasiswa:**
> *"Model ini mengasumsikan kapasitas bersifat linier sempurna ($\mu = \text{staf} \times \frac{60}{S}$) dan waktu penyelesaian pesanan bersifat independen. Pada operasional nyata gerai fisik, penambahan staf dari 1 menjadi 5 orang di dalam dapur kecil yang sempit dapat menimbulkan hambatan fisik (*diminishing returns* atau *space bottleneck*). Selain itu, model ini mengasumsikan waktu saji rata-rata gerai konstan, padahal pada jam sibuk ekstrem bahan baku gerai bisa saja habis sehingga waktu saji riil justru melambat."*

---

## BAGIAN 6. URUTAN REKOMENDASI MEMBACA KODE

Bagi mahasiswa yang baru membuka proyek ini, bacalah file-file berikut secara berurutan:

1. **[`routes/web.php`](file:///c:/Users/USER/flydine-juanda/routes/web.php#L64-L75):**
   *Apa yang dicari:* Pahami bagaimana URL `/admin/analytics/*` didaftarkan dan diarahkan ke controller di balik middleware `auth`.
2. **[`app/Services/CapacityPlanner.php`](file:///c:/Users/USER/flydine-juanda/app/Services/CapacityPlanner.php):**
   *Apa yang dicari:* Pelajari method murni `determineStatus()`, `calculateMinimumStaff()`, dan `calculate()` untuk memahami logika matematika tanpa terdistraksi urusan tampilan.
3. **[`app/Http/Controllers/AnalyticsController.php`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php):**
   *Apa yang dicari:* Amati method `drilldown()` (pembacaan filter awal) dan `drilldownOrders()` (validasi request, query builder, eager loading, dan output JSON).
4. **[`resources/views/layouts/admin.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/layouts/admin.blade.php):**
   *Apa yang dicari:* Amati bagaimana layout induk mengelola state `sidebarOpen` dan accordion menu `isAnalyticsActive` dengan Alpine.js.
5. **[`resources/views/components/analytics/filter-bar.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/components/analytics/filter-bar.blade.php):**
   *Apa yang dicari:* Lihat bagaimana komponen Blade menggunakan `@props` dan slot `$slot` untuk dipakai ulang di seluruh halaman.
6. **[`resources/views/admin/analytics/drilldown.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/drilldown.blade.php):**
   *Apa yang dicari:* Amati integrasi Chart.js di blok script dan bagaimana tabel HTML diikat ke array data Alpine menggunakan `x-for`.
7. **[`resources/views/admin/analytics/whatif.blade.php`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/whatif.blade.php):**
   *Apa yang dicari:* Pelajari pemanggilan endpoint AJAX POST dengan proteksi CSRF dan debounce saat slider digeser.
8. **[`database/seeders/DummyOrderSeeder.php`](file:///c:/Users/USER/flydine-juanda/database/seeders/DummyOrderSeeder.php):**
   *Apa yang dicari:* Lihat teknik penyiapan data pengujian realistis dan mekanisme pembersihan idempoten di method `clean()`.
9. **[`tests/Feature/CapacityPlannerTest.php`](file:///c:/Users/USER/flydine-juanda/tests/Feature/CapacityPlannerTest.php):**
   *Apa yang dicari:* Amati bagaimana pengujian otomatis memverifikasi batas status 69.9% vs 70.0% secara presisi.

---

## BAGIAN 7. LATIHAN MANDIRI MAHASISWA

Lakukan latihan mandiri berikut pada komputer lokal Anda untuk memperdalam pemahaman:

### Latihan 1: Mengubah Ambang Batas Status WASPADA
- **Tugas:** Ubah batas status WASPADA dari semula $70\%$ menjadi $60\%$.
- **File Target:** [`app/Services/CapacityPlanner.php#L26-L30`](file:///c:/Users/USER/flydine-juanda/app/Services/CapacityPlanner.php#L26-L30).
- **Hasil yang Diharapkan:** Buka halaman What-If di browser; tenant dengan utilisasi antara 60% s.d. 70% yang semula berlabel hijau AMAN kini berubah menjadi kuning WASPADA.

### Latihan 2: Menambahkan Batas Paginasi 100 Baris
- **Tugas:** Tambahkan opsi 100 baris per halaman pada tabel Drill-Down.
- **File Target:**
  1. [`app/Http/Controllers/AnalyticsController.php#L225`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php#L225): Ubah validasi `'per_page' => 'nullable|integer|in:10,25,50,100'`.
  2. [`resources/views/admin/analytics/drilldown.blade.php#L1274`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/drilldown.blade.php#L1274): Tambahkan elemen `<option :value="100">100 Baris</option>`.
- **Hasil yang Diharapkan:** Saat opsi 100 dipilih, tabel menampilkan 100 baris transaksi tanpa terjadi error validasi 422.

### Latihan 3: Mengubah Penanda Batas Waktu SOP Menjadi 20 Menit
- **Tugas:** Bandara memberlakukan kebijakan baru: batas toleransi waktu saji dinaikkan dari 15 menit menjadi 20 menit.
- **File Target:**
  1. [`app/Http/Controllers/AnalyticsController.php#L95`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php#L95): Ubah `diffMinutesSql <= 15` menjadi `<= 20`.
  2. [`resources/views/admin/analytics/drilldown.blade.php#L1627`](file:///c:/Users/USER/flydine-juanda/resources/views/admin/analytics/drilldown.blade.php#L1627): Geser garis batas merah pada meter linimasa drawer.
- **Hasil yang Diharapkan:** Persentase kepatuhan SLA meningkat karena pesanan yang selesai dalam tempo 16-20 menit kini terhitung patuh (*compliant*).

### Latihan 4: Mensimulasikan Serangan Akses Ilegal (RBAC Test)
- **Tugas:** Coba akses URL `/admin/analytics/drilldown` saat login sebagai akun staf tenant.
- **Langkah:** Ubah sementara role akun Anda di database menjadi `tenant_staff`.
- **Hasil yang Diharapkan:** Halaman menampilkan layar putih dengan kode error **403 | USER DOES NOT HAVE THE RIGHT ROLES**, membuktikan keamanan controller bekerja.

### Latihan 5: Menjalankan Ekspor Laporan PDF Resmi
- **Tugas:** Pastikan fitur warisan (*legacy*) cetak laporan tetap berfungsi pasca-pemecahan dashboard.
- **Langkah:** Pada halaman Ringkasan Eksekutif, klik tombol *"Export PDF"*.
- **Hasil yang Diharapkan:** Browser langsung mengunduh file dokumen PDF resmi berisi ringkasan metrik performa tenant.

---

## BAGIAN 8. ANALISIS RISIKO & TODO PEMELIHARAAN

### 8.1 Fitur Warisan yang Berhasil Dijaga (Zero Regression)
Seluruh fungsionalitas utama yang ada sebelum Langkah 0 tetap berfungsi normal tanpa mengalami kerusakan (*regression*):
1. **Fitur Ekspor PDF & Excel:** Method `export()` dan `exportExcel()` di [`ExecutiveDashboardController.php`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/ExecutiveDashboardController.php#L350-L450) tetap menghasilkan dokumen laporan yang valid.
2. **Ekspor Simulasi PDF:** Route `admin.executive-dashboard.export-simulation-pdf` tetap menerima parameter skenario dari halaman What-If dan mencetak matriks proyeksi finansial.
3. **Pipa ETL Harian:** Perintah `php artisan report:etl-daily` tetap berjalan lancar dan mengisi tabel `daily_tenant_summaries`.

### 8.2 Perbedaan Sintaks Mesin Database (MySQL vs. SQLite)
Di dalam kode [`AnalyticsController.php`](file:///c:/Users/USER/flydine-juanda/app/Http/Controllers/AnalyticsController.php#L41-L45) dan [`CapacityPlanner.php`](file:///c:/Users/USER/flydine-juanda/app/Services/CapacityPlanner.php#L90-L97), terdapat percabangan logika penanganan tanggal:
- **MySQL (Produksi):** Menggunakan fungsi bawaan `TIMESTAMPDIFF(MINUTE, ordered_at, ready_at)` dan `HOUR(ordered_at)`.
- **SQLite (Pengujian Otomatis):** Tidak memiliki fungsi `TIMESTAMPDIFF`, sehingga disubstitusi dengan formula epoch timestamp: `((strftime('%s', ready_at) - strftime('%s', ordered_at)) / 60)`.
- *Peringatan Mahasiswa:* Saat mempresentasikan proyek, jelaskan bahwa percabangan ini sengaja dibuat agar aplikasi dapat diuji secara kilat di lingkungan pengujian in-memory SQLite tanpa mengorbankan performa MySQL di server nyata.

### 8.3 Rekomendasi Pemeliharaan Mendatang (TODO List)
1. **Eksekusi Migration Indeks Komposit:** Jalankan proposal migrasi indeks komposit [`proposal_2026_10_16_add_composite_indexes_to_orders_table.php.example`](file:///c:/Users/USER/flydine-juanda/database/migrations/proposal_2026_10_16_add_composite_indexes_to_orders_table.php.example) saat volume transaksi di database operasional melampaui 100.000 baris.
2. **Pembersihan Data Uji Berkala:** Sebelum sistem diserahkan ke pengguna bandara sungguhan, pastikan menjalankan method pembersih `Database\Seeders\DummyOrderSeeder::clean()` untuk memusnahkan seluruh rekaman data berawalan `DUMMY-`.
3. **Penyempurnaan Penanganan Zona Waktu (Timezone Consistency):** Pastikan server web dan database MySQL selalu berada pada konfigurasi zona waktu yang selaras (`Asia/Jakarta` / UTC+7) agar fungsi filter jam pesan 00-23 tidak mengalami pergeseran waktu saat hari pergantian tanggal.

---
*Dokumen ini disusun sebagai bagian dari pemenuhan dokumentasi teknis sistem analitik operasional FlyDine Juanda.*
