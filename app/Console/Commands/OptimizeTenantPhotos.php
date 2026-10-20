<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class OptimizeTenantPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photos:optimize {--max-width=900 : Maximum width in pixels} {--quality=78 : JPEG compression quality (0-100)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resize and compress tenant photos in public/images/logos to optimize load speed';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dir = public_path('images/logos');

        if (!File::isDirectory($dir)) {
            $this->error("Directory not found: {$dir}");
            return Command::FAILURE;
        }

        $maxWidth = (int) $this->option('max-width');
        $quality = (int) $this->option('quality');

        $files = File::files($dir);
        $targetFiles = [];

        foreach ($files as $file) {
            $ext = strtolower($file->getExtension());
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $targetFiles[] = $file;
            }
        }

        if (empty($targetFiles)) {
            $this->warn("No image files found in {$dir}");
            return Command::SUCCESS;
        }

        $this->info("Found " . count($targetFiles) . " images in {$dir}");
        $this->info("Settings: Max Width = {$maxWidth}px, Quality = {$quality}%");
        $this->newLine();

        $totalBefore = 0;
        $totalAfter = 0;
        $optimizedCount = 0;

        $bar = $this->output->createProgressBar(count($targetFiles));
        $bar->start();

        foreach ($targetFiles as $file) {
            $path = $file->getRealPath();
            $sizeBefore = filesize($path);
            $totalBefore += $sizeBefore;

            $imageInfo = @getimagesize($path);
            if (!$imageInfo) {
                $totalAfter += $sizeBefore;
                $bar->advance();
                continue;
            }

            [$origWidth, $origHeight, $imageType] = $imageInfo;

            // Hanya proses jika ukuran > 200KB atau lebar > maxWidth
            if ($sizeBefore <= 200 * 1024 && $origWidth <= $maxWidth) {
                $totalAfter += $sizeBefore;
                $bar->advance();
                continue;
            }

            // Load source image
            $source = match ($imageType) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
                IMAGETYPE_PNG => @imagecreatefrompng($path),
                IMAGETYPE_WEBP => @imagecreatefromwebp($path),
                default => null,
            };

            if (!$source) {
                $totalAfter += $sizeBefore;
                $bar->advance();
                continue;
            }

            // Handle EXIF orientation (agar foto kamera HP tidak terbalik/miring)
            if ($imageType === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
                $exif = @exif_read_data($path);
                if (!empty($exif['Orientation'])) {
                    $source = match ($exif['Orientation']) {
                        3 => imagerotate($source, 180, 0),
                        6 => imagerotate($source, -90, 0),
                        8 => imagerotate($source, 90, 0),
                        default => $source,
                    };
                    $origWidth = imagesx($source);
                    $origHeight = imagesy($source);
                }
            }

            // Hitung dimensi baru proporsional
            if ($origWidth > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = (int) round(($origHeight * $maxWidth) / $origWidth);
            } else {
                $newWidth = $origWidth;
                $newHeight = $origHeight;
            }

            // Resample
            $thumb = imagecreatetruecolor($newWidth, $newHeight);

            // Handle transparency untuk PNG jika ada
            if ($imageType === IMAGETYPE_PNG) {
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
            }

            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

            // Simpan kembali
            if ($imageType === IMAGETYPE_PNG) {
                imagepng($thumb, $path, 8);
            } elseif ($imageType === IMAGETYPE_WEBP) {
                imagewebp($thumb, $path, $quality);
            } else {
                imagejpeg($thumb, $path, $quality);
            }

            imagedestroy($thumb);
            imagedestroy($source);

            clearstatcache(true, $path);
            $sizeAfter = filesize($path);
            $totalAfter += $sizeAfter;
            $optimizedCount++;

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $mbBefore = round($totalBefore / (1024 * 1024), 2);
        $mbAfter = round($totalAfter / (1024 * 1024), 2);
        $savedPercent = $totalBefore > 0 ? round((($totalBefore - $totalAfter) / $totalBefore) * 100, 1) : 0;
        $savedMb = round(($totalBefore - $totalAfter) / (1024 * 1024), 2);

        $this->table(
            ['Metrik', 'Nilai'],
            [
                ['Foto Dioptimasi', "{$optimizedCount} file"],
                ['Total Ukuran Sebelum', "{$mbBefore} MB"],
                ['Total Ukuran Setelah', "{$mbAfter} MB"],
                ['Hemat Kuota / Storage', "{$savedMb} MB ({$savedPercent}%)"],
            ]
        );

        $this->info("✅ Optimasi foto berhasil diselesaikan!");

        return Command::SUCCESS;
    }
}
