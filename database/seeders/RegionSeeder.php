<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('regions')->insert([
            [
                'region_id' => 1,
                'region_name' => 'Mau Forest Complex',
                'county' => 'Nakuru/Narok/Kericho',
                'ecosystem_type' => 'Montane Forest',
                'latitude' => -0.6030,
                'longitude' => 35.8360,
                'description' => 'A critical water tower in Kenya facing forest degradation and temperature increases.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'region_id' => 2,
                'region_name' => 'Tana Delta',
                'county' => 'Tana River',
                'ecosystem_type' => 'Wetland/Mangrove',
                'latitude' => -2.4930,
                'longitude' => 40.2110,
                'description' => 'Coastal delta sensitive to flooding, salinity shifts, and rainfall variability.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'region_id' => 3,
                'region_name' => 'Mt. Kenya region',
                'county' => 'Nyeri/Meru/Kirinyaga',
                'ecosystem_type' => 'Alpine/Montane Forest',
                'latitude' => -0.1522,
                'longitude' => 37.3084,
                'description' => 'Montane ecosystem sensitive to altitude temperature shifts and glacial retreat.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'region_id' => 4,
                'region_name' => 'Turkana',
                'county' => 'Turkana',
                'ecosystem_type' => 'Arid/Semi-Arid Scrubland',
                'latitude' => 3.1200,
                'longitude' => 35.6000,
                'description' => 'Arid and semi-arid savannah and scrubland ecosystem highly vulnerable to prolonged drought and water scarcity.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
