<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminFraudController extends Controller
{


    public function index()
    {

        abort_unless(
            auth()->user()->role === 'admin_ops',
            403
        );


        $customers = Customer::where(
            'is_blocked',
            true
        )
        ->withCount([
            'orders as cancelled_orders_count' => function ($query) {

                $query->where(
                    'status',
                    'dibatalkan'
                );

            }
        ])
        ->latest('blocked_at')
        ->get();



        return view(
            'admin.fraud-monitoring',
            compact('customers')
        );

    }





    public function unblock(Customer $customer)
    {

        abort_unless(
            auth()->user()->role === 'admin_ops',
            403
        );


        $customer->update([

            'is_blocked' => false,

            'blocked_reason' => null,

            'blocked_at' => null,

        ]);



        return redirect()
            ->back()
            ->with(
                'success',
                'Customer berhasil dibuka blokirnya.'
            );

    }


}
