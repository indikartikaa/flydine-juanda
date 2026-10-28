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
        'notes',
        'status',
        'payment_method',
        'is_paid',
        'pickup_method',
        'delivery_location_id',
        'delivery_fee',
        'heading_to_tenant_at',
        'ready_at',
        'auto_cancel_at',
        'total_amount',
        'ordered_at',
        'completed_at',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'boarding_time' => 'datetime',
        'heading_to_tenant_at' => 'datetime',
        'ready_at' => 'datetime',
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

    public function deliveryLocation()
    {
        return $this->belongsTo(DeliveryLocation::class);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'qris' => 'QRIS Instant (GPN)',
            'transfer_bca' => 'BCA Virtual Account',
            'transfer_mandiri' => 'Mandiri Virtual Account',
            'transfer_bri' => 'BRI Virtual Account (BRIVA)',
            'transfer_bni' => 'BNI Virtual Account',
            'transfer' => 'Transfer Bank Virtual Account',
            default => strtoupper((string) ($this->payment_method ?? 'QRIS')),
        };
    }

    public function getSelectedBankAttribute(): string
    {
        if (str_starts_with((string) $this->payment_method, 'transfer_')) {
            return str_replace('transfer_', '', (string) $this->payment_method);
        }
        return 'bca';
    }
}