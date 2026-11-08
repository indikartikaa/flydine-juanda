<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;
use Carbon\Carbon;

class FraudDetectionService
{

    protected ActivityLogger $logger;


    public function __construct(ActivityLogger $logger)
    {
        $this->logger = $logger;
    }


    /**
     * Mencatat pembatalan pesanan ke log audit sistem fraud dan mengevaluasi status blokir akun
     * (TC-ORD-08, TC-BVA-07, TC-BVA-08, TC-BVA-09)
     */
    public function recordCancelledOrder(Order $order): bool
    {
        $customerId = $order->customer_id;
        $cancelledOrders = 1;

        if ($customerId) {
            $customer = Customer::find($customerId);
            $query = Order::where('customer_id', $customerId)
                ->where('status', 'dibatalkan')
                ->where('updated_at', '>=', Carbon::now()->subMinutes(30));

            if ($customer && $customer->unblocked_at) {
                $query->where('updated_at', '>', $customer->unblocked_at);
            }

            $cancelledOrders = $query->count();
        }

        // Catat aktivitas pembatalan ke activity_logs untuk pemantauan deteksi fraud
        $this->logger->log(
            'ORDER_CANCELLED',
            'orders',
            $order->id,
            "Pesanan #{$order->order_code} dibatalkan sebelum bayar" . ($customerId ? " (Pelanggan ID: {$customerId}, akumulasi: {$cancelledOrders}x pembatalan dalam 30 menit, is_blocked: " . ($cancelledOrders >= 5 ? '1' : '0') . ")." : ".")
        );

        // Jika akumulasi pembatalan mencapai 5 kali dalam 30 menit, otomatis blokir akun customer
        if ($customerId && $cancelledOrders >= 5) {
            $this->blockCustomer($customerId, $cancelledOrders);
            return true;
        }

        return false;
    }

    /**
     * Mengecek pola order mencurigakan
     *
     * Rule:
     * Customer melakukan 5 order dibatalkan
     * dalam waktu 30 menit
     */
    public function checkCancelledOrderPattern($customerId): bool
    {
        $customer = Customer::find($customerId);
        if (!$customer) {
            return false;
        }

        $query = Order::where('customer_id', $customerId)
            ->where('status', 'dibatalkan')
            ->where(
                'updated_at',
                '>=',
                Carbon::now()->subMinutes(30)
            );

        if ($customer->unblocked_at) {
            $query->where('updated_at', '>', $customer->unblocked_at);
        } else {
            $lastUnblock = \App\Models\ActivityLog::where('action', 'CUSTOMER_UNBLOCKED')
                ->where('table_name', 'customers')
                ->where('record_id', $customerId)
                ->latest()
                ->first();
            if ($lastUnblock) {
                $query->where('updated_at', '>', $lastUnblock->created_at);
            }
        }

        $cancelledOrders = $query->count();

        if ($cancelledOrders >= 5) {
            $this->blockCustomer(
                $customerId,
                $cancelledOrders
            );
            return true;
        }

        return false;
    }

    /**
     * Blokir customer otomatis
     */
    protected function blockCustomer($customerId, $cancelledOrders): void
    {
        $customer = Customer::find($customerId);

        if (!$customer) {
            return;
        }

        // Hindari update berulang
        if ($customer->is_blocked) {
            return;
        }

        $customer->update([
            'is_blocked' => true,
            'blocked_reason' => "Terdeteksi {$cancelledOrders} order dibatalkan dalam 30 menit",
            'blocked_at' => now(),
            'unblocked_at' => null,
        ]);

        $this->logger->log(
            'CUSTOMER_BLOCKED',
            'customers',
            $customerId,
            "Customer otomatis diblokir karena {$cancelledOrders} order dibatalkan dalam 30 menit"
        );
    }

}
