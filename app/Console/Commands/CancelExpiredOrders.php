<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membatalkan pesanan yang statusnya menunggu dan melewati batas auto_cancel_at';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredOrders = Order::where('status', 'menunggu')
            ->where('auto_cancel_at', '<=', now())
            ->get();

        $count = 0;
        foreach ($expiredOrders as $order) {
            $order->update(['status' => 'dibatalkan']);
            $count++;
        }

        if ($count > 0) {
            $this->info("Berhasil membatalkan {$count} pesanan kedaluwarsa.");
        }
    }
}
