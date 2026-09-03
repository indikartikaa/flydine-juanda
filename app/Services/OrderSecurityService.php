<?php

namespace App\Services;

use App\Models\Order;

class OrderSecurityService
{

    protected ActivityLogger $logger;


    public function __construct(ActivityLogger $logger)
    {
        $this->logger = $logger;
    }


    public function checkActiveOrderLimit($customerId, $orderId = null)
    {

        $activeOrders = Order::where('customer_id', $customerId)

            ->whereIn('status', [
                'menunggu',
                'diproses',
                'siap'
            ])

            ->count();


        if ($activeOrders >= 5) {


            $this->logger->log(

                'ACTIVE_ORDER_LIMIT_REACHED',

                'orders',

                $orderId,

                "Customer ID {$customerId} memiliki {$activeOrders} order aktif dan mencoba membuat order baru"

            );


            return false;

        }


        return true;

    }

}
