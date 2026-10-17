<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('dummy:clean', function () {
    $this->info('Membersihkan data dummy (pesanan, item, komplain, customer uji)...');
    $stats = \Database\Seeders\DummyOrderSeeder::clean();
    $this->info(sprintf(
        "Pembersihan selesai! Pesanan terhapus: %d, Item: %d, Komplain: %d, Customer uji: %d",
        $stats['deletedOrders'],
        $stats['deletedItems'],
        $stats['deletedComplaints'],
        $stats['deletedCustomers']
    ));
    $this->info('Statistik total_orders pada profil customer telah diselaraskan.');
})->purpose('Membersihkan seluruh pesanan dummy, komplain dummy, dan menyelaraskan segmentasi pelanggan');

Schedule::command('orders:cancel-expired')->everyMinute();
Schedule::command('report:etl-daily')->dailyAt('00:05');
