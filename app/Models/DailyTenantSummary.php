<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTenantSummary extends Model
{
    use HasFactory;

    protected $table = 'daily_tenant_summaries';

    protected $fillable = [
        'summary_date',
        'tenant_id',
        'total_orders',
        'paid_orders',
        'cancelled_orders',
        'total_revenue',
        'ready_orders',
        'sla_compliant_orders',
        'avg_sla_minutes',
    ];

    protected $casts = [
        'summary_date' => 'date',
        'total_orders' => 'integer',
        'paid_orders' => 'integer',
        'cancelled_orders' => 'integer',
        'total_revenue' => 'decimal:2',
        'ready_orders' => 'integer',
        'sla_compliant_orders' => 'integer',
        'avg_sla_minutes' => 'decimal:2',
    ];

    /**
     * Relasi ke Master Tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
