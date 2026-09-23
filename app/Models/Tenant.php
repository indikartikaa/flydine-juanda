<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_code',
        'name',
        'company_name',
        'category',
        'terminal',
        'zone',
        'floor_location',
        'opening_time',
        'closing_time',
        'phone',
        'pic_name',
        'pic_email',
        'pic_phone',
        'logo',
        'is_active',
        'delivery_fee',
        'contract_start',
        'contract_end',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi: Satu Tenant bisa dikelola oleh banyak User (Tenant Staff).
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relasi: Satu Tenant memiliki banyak Produk pada katalognya.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relasi: Satu Tenant menerima banyak Pesanan.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Helper: Cek apakah tenant sedang buka (berdasarkan waktu saat ini)
     */
    public function isOpen()
    {
        if (!$this->is_active) return false;
        if (empty($this->opening_time) || empty($this->closing_time)) return true;
        
        $tz = config('app.timezone', 'Asia/Jakarta');
        $now = now($tz)->format('H:i:s');

        $open = substr($this->opening_time, 0, 5) . ':00';
        $close = substr($this->closing_time, 0, 5) . ':59';
        
        if ($close < $open) {
            // Jam operasional melewati tengah malam (misal: 18:00 s.d. 02:00)
            return $now >= $open || $now <= $close;
        }
        return $now >= $open && $now <= $close;
    }
}