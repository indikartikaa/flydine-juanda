<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class AdminFraudController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $query = Customer::where('is_blocked', true)
            ->withCount([
                'orders as cancelled_orders_count' => function ($q) {
                    $q->where('status', 'dibatalkan');
                }
            ]);

        // Search Filter (Nama pelanggan, No HP, Alasan Pemblokiran)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('blocked_reason', 'like', "%{$search}%");
            });
        }

        // Quick Preset Filter
        if ($request->filled('filter')) {
            if ($request->filter === 'high_cancel') {
                $query->whereHas('orders', function ($q) {
                    $q->where('status', 'dibatalkan');
                }, '>=', 5);
            } elseif ($request->filter === 'recent') {
                $query->where('blocked_at', '>=', now()->subDays(7));
            }
        }

        // Server-side lightweight pagination (10 items per page)
        $customers = $query->latest('blocked_at')->paginate(10)->withQueryString();

        // Global KPI Stats for Security Dashboard
        $total_blocked = Customer::where('is_blocked', true)->count();
        $total_active = Customer::where('is_blocked', false)->count();
        $total_cancelled_orders = Order::where('status', 'dibatalkan')->count();
        $recent_blocked_7d = Customer::where('is_blocked', true)
            ->where('blocked_at', '>=', now()->subDays(7))
            ->count();

        return view('admin.fraud-monitoring', compact(
            'customers',
            'total_blocked',
            'total_active',
            'total_cancelled_orders',
            'recent_blocked_7d'
        ));
    }

    public function unblock(Customer $customer, ActivityLogger $logger)
    {
        abort_unless(auth()->user()->role === 'admin_ops', 403);

        $customerName = $customer->name ?? 'Pelanggan';
        $customerPhone = $customer->phone_number;

        $customer->update([
            'is_blocked' => false,
            'blocked_reason' => null,
            'blocked_at' => null,
        ]);

        $logger->log(
            'CUSTOMER_UNBLOCKED',
            'customers',
            $customer->id,
            "Akun pelanggan {$customerName} ({$customerPhone}) telah dibuka blokirnya oleh Admin Ops"
        );

        return redirect()
            ->back()
            ->with('success', "Akun {$customerName} ({$customerPhone}) berhasil dibuka blokirnya.");
    }
}
