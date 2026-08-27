<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    // Pastikan Laravel membaca tabel yang benar
    protected $table = 'activity_logs';

    // Kolom yang diizinkan untuk diisi secara massal (Mass Assignment)
    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'description',
        'ip_address',
        'user_agent'
    ];

    /**
     * Relasi ke Model User.
     * Log ini mencatat aktivitas milik siapa.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}