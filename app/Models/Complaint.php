<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_code',
        'order_id',
        'category',
        'description',
        'status',
        'handled_by_user_id',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Relasi Komplain ke Pesanan dan Staf Penanganan
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by_user_id');
    }
}