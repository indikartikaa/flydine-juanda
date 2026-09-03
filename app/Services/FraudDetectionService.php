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
