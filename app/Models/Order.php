<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'tenant_id',
        'customer_id',
        'customer_name',
        'flight_number',
        'gate',
        'boarding_time',
        'status',
        'heading_to_tenant_at',
        'auto_cancel_at',
        'total_amount',
        'ordered_at',
        'completed_at',
    ];

    protected $casts = [
        'boarding_time' => 'datetime',
        'heading_to_tenant_at' => 'datetime',
        'auto_cancel_at' => 'datetime',
        'ordered_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Relasi Header Pesanan ke Entitas Lainnya
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}