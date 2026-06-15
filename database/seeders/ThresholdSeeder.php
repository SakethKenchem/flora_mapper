<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThresholdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vulnerability_thresholds')->insert([
            [
                'threshold_id' => 1,
                'threshold_name' => 'Default Vulnerability Thresholds',
                'low_min' => 0.00,
                'low_max' => 30.00,
                'moderate_min' => 31.00,
                'moderate_max' => 60.00,
                'high_min' => 61.00,
                'high_max' => 100.00,
                'created_by' => 3, // SYSTEM_ADMINISTRATOR
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
