<?php

namespace Database\Seeders;

use App\Models\DeliveryLocation;
use Illuminate\Database\Seeder;

class DeliveryLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $terminal1Locations = [
            'Public T1',
            'Central Lobby T1',
            'Lobby Departure T1',
            'Lobby Arrival T1',
            'Departure T1 A',
            'Departure T1 B',
            'Arrival T1 A',
            'Arrival T1 B',
            'Boarding Lounge Lantai 2 (Gate 1) T1',
            'Boarding Lounge Lantai 2 (Gate 2&3) T1',
            'Boarding Lounge Lantai 2 (Gate 4) T1',
            'Boarding Lounge Lantai 2 (Gate 5) T1',
            'Boarding Lounge Lantai 2 (Gate 6) T1',
            'Boarding Lounge Lantai 2 (Gate 7) T1',
            'Boarding Lounge Lantai 2 (Gate 8) T1',
            'Boarding Lounge Lantai 2 (Gate 9) T1',
            'Boarding Lounge Lantai 2 (Gate 11) T1',
            'Boarding Lounge Lantai 2 (Gate 12) T1',
            'Boarding Lounge Lantai 2 (Gate 12-13) T1',
            'Boarding Lounge Lantai 2 (Gate 13) T1',
            'Boarding Lounge Lantai 2 (Gate 15) T1',
        ];

        foreach ($terminal1Locations as $loc) {
            DeliveryLocation::firstOrCreate([
                'terminal' => '1',
                'name' => $loc,
            ]);
        }

        $terminal2Locations = [
            'Public T2',
            'Central Lobby T2',
            'Departure Domestik T2',
            'Departure International T2',
            'Arrival Domestik T2',
            'Arrival International T2',
            'Koridor Keberangkatan T2',
            'Boarding Lounge Lantai 2 T2',
        ];

        foreach ($terminal2Locations as $loc) {
            DeliveryLocation::firstOrCreate([
                'terminal' => '2',
                'name' => $loc,
            ]);
        }
    }
}
