<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TenantPhotoSeeder extends Seeder
{
    /**
     * Update kolom 'logo' pada tabel tenants berdasarkan foto dokumentasi.
     * Foto disimpan di storage/app/public/tenants/ dengan format nama: {tenant_code}.jpg
     * Kolom logo diisi path relatif: tenants/{tenant_code}.jpg
     */
    public function run(): void
    {
        // Foto disimpan di public/images/logos/ agar konsisten
        // dengan cara upload dari ProfileController & TenantOrderController
        $photoDir = public_path('images/logos');

        if (!File::isDirectory($photoDir)) {
            $this->command->error("Direktori foto tidak ditemukan: {$photoDir}");
            return;
        }

        $photos = File::files($photoDir);
        $updated = 0;
        $notFound = [];

        foreach ($photos as $photo) {
            $filename = $photo->getFilename(); // e.g. FB-T1-02-01.jpg
            $extension = strtolower($photo->getExtension());

            // Hanya proses file gambar
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                continue;
            }

            // Hanya proses file yang diawali kode tenant (misal FB-...)
            if (!str_starts_with($filename, 'FB-')) {
                continue;
            }

            $tenantCode = pathinfo($filename, PATHINFO_FILENAME); // e.g. FB-T1-02-01

            $tenant = Tenant::where('tenant_code', $tenantCode)->first();

            if ($tenant) {
                $tenant->update([
                    'logo' => 'images/logos/' . $filename,
                ]);
                $updated++;
                $this->command->info("✅ {$tenantCode} → {$tenant->name} → logo diperbarui");
            } else {
                $notFound[] = $filename;
                $this->command->warn("⚠️  Tenant dengan kode '{$tenantCode}' belum ada di database.");
            }
        }

        $this->command->newLine();
        $this->command->info("========================================");
        $this->command->info("Total foto tenant diproses : " . ($updated + count($notFound)));
        $this->command->info("Berhasil diperbarui        : {$updated}");

        if (!empty($notFound)) {
            $this->command->warn("Belum ada di database      : " . count($notFound));
            $this->command->warn("File: " . implode(', ', $notFound));
        }

        $this->command->info("========================================");
    }
}
