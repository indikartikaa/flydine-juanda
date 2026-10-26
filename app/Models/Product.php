<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'category',
        'description',
        'price',
        'image',
        'note',
        'stock',
        'preparation_time',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_available' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Otomatis fallback ke foto terstandarisasi di public/images/products/ jika database lokal belum disinkronkan.
     */
    public function getImageAttribute($value)
    {
        if (!empty($value) && file_exists(public_path($value))) {
            return $value;
        }

        $tSlug = \Illuminate\Support\Str::slug($this->tenant?->name ?? '');
        $pSlug = \Illuminate\Support\Str::slug($this->name ?? '');

        // Cek pola standar: {tenant-slug}-{product-slug}.png
        $candidate = "images/products/{$tSlug}-{$pSlug}.png";
        if (file_exists(public_path($candidate))) {
            return $candidate;
        }

        return $value;
    }
}
