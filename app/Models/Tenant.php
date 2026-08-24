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
        'floor_location',
        'opening_time',
        'closing_time',
        'phone',
        'is_active',
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
}