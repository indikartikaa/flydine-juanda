<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductPhotoSeeder extends Seeder
{
    /**
     * Update kolom 'image' pada tabel products berdasarkan foto di public/images/products/.
     * Format nama file: {nama-menu-slug}.(jpg|png|webp|jpeg)
     * Contoh: cheese-cream-puff.jpg, pie-cream-puff-vanilla.png
     */
    public function run(): void
    {
        $photoDir = public_path('images/products');

        if (!File::isDirectory($photoDir)) {
            $this->command->warn("Direktori foto produk tidak ditemukan: {$photoDir}");
            return;
        }

        $photos = File::files($photoDir);
        $totalUpdated = 0;

        foreach ($photos as $photo) {
            $filename = $photo->getFilename();
            $extension = strtolower($photo->getExtension());

            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                continue;
            }

            $rawName = pathinfo($filename, PATHINFO_FILENAME);
            $slugName = Str::slug($rawName);

            // Cari produk yang cocok dengan slug nama menu
            $products = Product::all()->filter(function ($prod) use ($slugName) {
                return Str::slug($prod->name) === $slugName;
            });

            if ($products->isNotEmpty()) {
                $relativeImagePath = 'images/products/' . $filename;
                foreach ($products as $product) {
                    $product->update([
                        'image' => $relativeImagePath,
                    ]);
                    $totalUpdated++;
                }
                $this->command->info("✅ {$filename} → terpasang pada {$products->count()} menu '{$products->first()->name}'");
            } else {
                $this->command->warn("⚠️  Tidak ditemukan menu di database yang cocok dengan file: {$filename}");
            }
        }

        $this->command->info("Selesai! Total {$totalUpdated} produk berhasil diperbarui fotonya.");
    }
}
