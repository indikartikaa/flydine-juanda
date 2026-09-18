<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportTenantData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:tenant-data {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import tenant and menu data from CSV or Excel file or sync locations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');
        
        if ($file === 'sync-locations' || !$file) {
            $this->info('Memulai sinkronisasi lokasi Gate dan Zona untuk seluruh 60 Tenant...');

            DB::transaction(function () {
                // Semua 60 tenant adalah Terminal 1
                DB::table('tenants')->where('id', '<=', 60)->update(['terminal' => 'T1']);

                // Lantai 1 (Landside)
                DB::table('tenants')->where('id', 1)->update(['zone' => 'Landside', 'floor_location' => 'Lantai 1 - Kedatangan 1A']);
                DB::table('tenants')->whereIn('id', [2, 3])->update(['zone' => 'Landside', 'floor_location' => 'Lantai 1 - Keberangkatan 1A']);
                DB::table('tenants')->whereIn('id', [4, 5])->update(['zone' => 'Landside', 'floor_location' => 'Lantai 1 - Keberangkatan 1B']);
                DB::table('tenants')->whereIn('id', [6, 7, 8, 9, 10])->update(['zone' => 'Landside', 'floor_location' => 'Lantai 1 - Keberangkatan 1C']);
                DB::table('tenants')->whereIn('id', [11, 12, 13, 14, 15])->update(['zone' => 'Landside', 'floor_location' => 'Lantai 1 - Anjungan']);
                DB::table('tenants')->whereBetween('id', [16, 23])->update(['zone' => 'Landside', 'floor_location' => 'Lantai 1 - Kedatangan 1B']);

                // Lantai 2 (Airside)
                DB::table('tenants')->where('id', 24)->update(['zone' => 'Airside', 'floor_location' => 'Lantai 2 - Gate 1']);
                DB::table('tenants')->whereIn('id', [25, 26])->update(['zone' => 'Airside', 'floor_location' => 'Lantai 2 - Gate 4']);
                DB::table('tenants')->whereIn('id', [27, 28])->update(['zone' => 'Airside', 'floor_location' => 'Lantai 2 - Gate 5']);
                DB::table('tenants')->where('id', 29)->update(['zone' => 'Airside', 'floor_location' => 'Lantai 2 - Check Point Boarding']);
                DB::table('tenants')->where('id', 30)->update(['zone' => 'Airside', 'floor_location' => 'Lantai 2 - Gate 6']);
                DB::table('tenants')->where('id', 31)->update(['zone' => 'Airside', 'floor_location' => 'Lantai 2 - Gate 7']);
                DB::table('tenants')->where('id', 32)->update(['zone' => 'Airside', 'floor_location' => 'Lantai 2 - Gate 8']);
                DB::table('tenants')->whereBetween('id', [33, 51])->update(['zone' => 'Airside', 'floor_location' => 'Lantai 2 - Gate 8 (Food Court)']);
                DB::table('tenants')->where('id', 52)->update(['zone' => 'Airside', 'floor_location' => 'Lantai 2 - Gate 10']);
                DB::table('tenants')->whereIn('id', [53, 54])->update(['zone' => 'Airside', 'floor_location' => 'Lantai 2 - Gate 11']);
                DB::table('tenants')->whereBetween('id', [55, 60])->update(['zone' => 'Airside', 'floor_location' => 'Lantai 2 - Gate 12']);
            });

            $this->info('Sinkronisasi Gate 1 s/d Gate 12 selesai dengan sukses!');
            return Command::SUCCESS;
        }

        $this->info("Memulai proses import dari file: {$file}...");
        return Command::SUCCESS;
    }
}
