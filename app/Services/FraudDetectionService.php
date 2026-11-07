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
            $cancelledOrders = Order::where('customer_id', $customerId)
                ->where('status', 'dibatalkan')
                ->where('updated_at', '>=', Carbon::now()->subMinutes(30))
                ->count();
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

        $cancelledOrders = Order::where('customer_id', $customerId)

            ->where('status', 'dibatalkan')

            ->where(
                'updated_at',
                '>=',
                Carbon::now()->subMinutes(30)
            )

            ->count();



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

            'blocked_reason' =>
                "Terdeteksi {$cancelledOrders} order dibatalkan dalam 30 menit",

            'blocked_at' => now(),

        ]);



        $this->logger->log(

            'CUSTOMER_BLOCKED',

            'customers',

            $customerId,

            "Customer otomatis diblokir karena {$cancelledOrders} order dibatalkan dalam 30 menit"

        );

    }

}
