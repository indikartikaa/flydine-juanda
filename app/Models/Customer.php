<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'name',
        'first_order_at',
        'last_order_at',
        'total_orders',
    ];

    protected $casts = [
        'first_order_at' => 'datetime',
        'last_order_at' => 'datetime',
        'total_orders' => 'integer',
    ];

    /**
     * Relasi: Satu profil pelanggan (Customer) bisa membuat banyak pesanan.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}