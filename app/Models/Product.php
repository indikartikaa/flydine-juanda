<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public const CUSTOMIZATION_PRESETS = [
        'temperature' => [
            'label' => 'Suhu Penyajian',
            'short' => 'Suhu',
            'icon' => '🌡️',
            'choices' => ['Iced', 'Hot'],
            'default' => 'Iced',
        ],
        'size' => [
            'label' => 'Ukuran (Size)',
            'short' => 'Ukuran',
            'icon' => '🥤',
            'choices' => ['Regular', 'Large'],
            'default' => 'Regular',
        ],
        'milk' => [
            'label' => 'Pilihan Susu (Milk)',
            'short' => 'Milk',
            'icon' => '🥛',
            'choices' => ['Fresh Milk', 'Oat Milk', 'Soy Milk'],
            'default' => 'Fresh Milk',
        ],
        'sugar' => [
            'label' => 'Level Gula (Sugar)',
            'short' => 'Sugar',
            'icon' => '🍬',
            'choices' => ['Normal Sugar', 'Less Sugar', 'No Sugar'],
            'default' => 'Normal Sugar',
        ],
        'ice' => [
            'label' => 'Level Es (Ice)',
            'short' => 'Ice',
            'icon' => '🧊',
            'choices' => ['Normal Ice', 'Less Ice', 'No Ice'],
            'default' => 'Normal Ice',
        ],
        'spicy' => [
            'label' => 'Level Pedas',
            'short' => 'Pedas',
            'icon' => '🌶️',
            'choices' => ['Tidak Pedas', 'Sedang', 'Pedas'],
            'default' => 'Sedang',
        ],
        'chicken' => [
            'label' => 'Potongan Ayam',
            'short' => 'Potongan',
            'icon' => '🍗',
            'choices' => ['Dada', 'Paha Atas', 'Paha Bawah', 'Sayap'],
            'default' => 'Dada',
        ],
        'topping' => [
            'label' => 'Pilihan Topping',
            'short' => 'Topping',
            'icon' => '🧋',
            'choices' => ['Tanpa Topping', 'Pearl / Boba', 'Grass Jelly', 'Pudding'],
            'default' => 'Tanpa Topping',
        ],
    ];

    /**
     * Mengembalikan daftar grup varian yang sudah dinormalisasi menjadi format seragam:
     * [['label' => 'Suhu Penyajian', 'choices' => ['Iced', 'Hot']], ...]
     */
    public function getNormalizedCustomizationGroups(): array
    {
        $raw = $this->customization_options;
        if (empty($raw) || !is_array($raw)) {
            return [];
        }

        $groups = [];
        foreach ($raw as $entry) {
            if (is_string($entry) && isset(self::CUSTOMIZATION_PRESETS[$entry])) {
                $preset = self::CUSTOMIZATION_PRESETS[$entry];
                $groups[] = [
                    'label' => $preset['label'],
                    'choices' => $preset['choices'],
                ];
            } elseif (is_array($entry) && !empty($entry['label'])) {
                $label = trim((string) $entry['label']);
                $rawChoices = $entry['choices'] ?? [];
                if (is_string($rawChoices)) {
                    $rawChoices = explode(',', $rawChoices);
                }
                $choices = array_values(array_filter(array_map(fn($c) => trim((string) $c), (array) $rawChoices), fn($c) => $c !== ''));
                if ($label !== '' && count($choices) > 0) {
                    $groups[] = [
                        'label' => $label,
                        'choices' => $choices,
                    ];
                }
            }
        }

        return $groups;
    }

    protected $fillable = [
        'tenant_id',
        'name',
        'category',
        'description',
        'price',
        'image',
        'note',
        'customization_options',
        'stock',
        'preparation_time',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_available' => 'boolean',
        'customization_options' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Otomatis mengenali foto hasil upload tenant (storage/app/public atau public/storage)
     * serta fallback ke foto terstandarisasi di public/images/products/ jika belum ada foto upload.
     */
    public function getImageAttribute($value)
    {
        if (!empty($value)) {
            if (
                str_starts_with($value, 'http://') ||
                str_starts_with($value, 'https://') ||
                file_exists(public_path($value)) ||
                file_exists(public_path('storage/' . $value)) ||
                file_exists(storage_path('app/public/' . $value)) ||
                \Illuminate\Support\Facades\Storage::disk('public')->exists($value)
            ) {
                return $value;
            }
        }

        $tSlug = \Illuminate\Support\Str::slug($this->tenant?->name ?? '');
        $pSlug = \Illuminate\Support\Str::slug($this->name ?? '');

        // Cek pola standar: {tenant-slug}-{product-slug}.png
        $candidate = "images/products/{$tSlug}-{$pSlug}.png";
        if (file_exists(public_path($candidate))) {
            return $candidate;
        }

        return null;
    }
}
