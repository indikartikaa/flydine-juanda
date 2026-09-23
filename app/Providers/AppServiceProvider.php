<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('components.pagination');

        // Otomatis hapus session 'order_code' jika pesanan telah selesai, dibatalkan, atau ditolak
        view()->composer(['customer.*', 'customer.pages.*'], function ($view) {
            if (session()->has('order_code')) {
                $code = session('order_code');
                $activeOrder = \App\Models\Order::where('order_code', $code)->first();
                if (!$activeOrder || in_array($activeOrder->status, ['selesai', 'dibatalkan', 'ditolak'])) {
                    session()->forget('order_code');
                }
            }
        });
    }
}
