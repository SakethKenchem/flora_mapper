<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatasetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Datasets Meta
        DB::table('datasets')->insert([
            [
                'dataset_id' => 1,
                'uploaded_by' => 2, // Researcher Dr. Jane Mwangi
                'dataset_name' => 'Kenya National Climate Grid 2025-2026',
                'dataset_type' => 'Climate',
                'source_name' => 'Kenya Meteorological Department (KMD)',
                'upload_status' => 'Validated',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dataset_id' => 2,
                'uploaded_by' => 2, // Researcher Dr. Jane Mwangi
                'dataset_name' => 'East African NDVI Vegetation Cover Indices',
                'dataset_type' => 'Vegetation',
                'source_name' => 'MODIS satellite imagery / NASA',
                'upload_status' => 'Validated',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 2. Monthly Climate Records (Region 1: Mau Forest Complex, 2: Tana Delta, 3: Mt. Kenya, 4: Turkana)
        $climateRecords = [];
        $vegRecords = [];

        // Region 1: Mau Forest Complex (Ecosystem: Montane Forest)
        // Expected characteristics: Mild temps, high rainfall, low drought index
        for ($month = 1; $month <= 12; $month++) {
            $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
            $climateRecords[] = [
                'dataset_id' => 1,
                'region_id' => 1,
                'record_date' => "2025-{$monthStr}-15",
                'temperature_celsius' => 20.0 + ($month % 3) + (rand(-10, 10) / 10),
                'rainfall_mm' => 130.0 + ($month * 5) + rand(-15, 15),
                'humidity_percent' => 82.0 + rand(-5, 5),
                'drought_index' => 0.12 + (rand(-5, 5) / 100),
                'flood_risk_level' => 'Low',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $vegRecords[] = [
                'dataset_id' => 2,
                'region_id' => 1,
                'record_date' => "2025-{$monthStr}-15",
                'ndvi_value' => 0.720 + (rand(-40, 40) / 1000),
                'vegetation_cover_percent' => 82.50 + rand(-3, 3),
                'vegetation_condition' => 'Excellent',
                'data_source' => 'MODIS Terra',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Region 2: Tana Delta (Ecosystem: Wetland/Mangrove)
        // Expected characteristics: High temps, moderate rainfall, moderate drought
        for ($month = 1; $month <= 12; $month++) {
            $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
            $climateRecords[] = [
                'dataset_id' => 1,
                'region_id' => 2,
                'record_date' => "2025-{$monthStr}-15",
                'temperature_celsius' => 29.5 + ($month % 2) + (rand(-10, 10) / 10),
                'rainfall_mm' => 70.0 + ($month * 3) + rand(-10, 10),
                'humidity_percent' => 88.0 + rand(-3, 3),
                'drought_index' => 0.38 + (rand(-8, 8) / 100),
                'flood_risk_level' => 'Moderate',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $vegRecords[] = [
                'dataset_id' => 2,
                'region_id' => 2,
                'record_date' => "2025-{$monthStr}-15",
                'ndvi_value' => 0.520 + (rand(-50, 50) / 1000),
                'vegetation_cover_percent' => 55.40 + rand(-5, 5),
                'vegetation_condition' => 'Good',
                'data_source' => 'MODIS Aqua',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Region 3: Mt. Kenya region (Ecosystem: Alpine/Montane Forest)
        // Expected characteristics: Cool temps, high rainfall, low drought index
        for ($month = 1; $month <= 12; $month++) {
            $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
            $climateRecords[] = [
                'dataset_id' => 1,
                'region_id' => 3,
                'record_date' => "2025-{$monthStr}-15",
                'temperature_celsius' => 13.5 + ($month % 3) + (rand(-8, 8) / 10),
                'rainfall_mm' => 110.0 + ($month * 4) + rand(-20, 20),
                'humidity_percent' => 75.0 + rand(-6, 6),
                'drought_index' => 0.08 + (rand(-3, 3) / 100),
                'flood_risk_level' => 'Low',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $vegRecords[] = [
                'dataset_id' => 2,
                'region_id' => 3,
                'record_date' => "2025-{$monthStr}-15",
                'ndvi_value' => 0.780 + (rand(-30, 30) / 1000),
                'vegetation_cover_percent' => 86.20 + rand(-2, 2),
                'vegetation_condition' => 'Excellent',
                'data_source' => 'MODIS Terra',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Region 4: Turkana (Ecosystem: Arid/Semi-Arid Scrubland)
        // Expected characteristics: Very high temps, very low rainfall, high drought index
        for ($month = 1; $month <= 12; $month++) {
            $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
            $climateRecords[] = [
                'dataset_id' => 1,
                'region_id' => 4,
                'record_date' => "2025-{$monthStr}-15",
                'temperature_celsius' => 35.8 + ($month % 2) + (rand(-12, 12) / 10),
                'rainfall_mm' => 15.0 + (rand(-8, 8)),
                'humidity_percent' => 35.0 + rand(-8, 8),
                'drought_index' => 0.85 + (rand(-10, 10) / 100),
                'flood_risk_level' => 'None',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $vegRecords[] = [
                'dataset_id' => 2,
                'region_id' => 4,
                'record_date' => "2025-{$monthStr}-15",
                'ndvi_value' => 0.180 + (rand(-25, 25) / 1000),
                'vegetation_cover_percent' => 18.20 + rand(-4, 4),
                'vegetation_condition' => 'Critical',
                'data_source' => 'MODIS Aqua',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('climate_data')->insert($climateRecords);
        DB::table('vegetation_data')->insert($vegRecords);

        // 3. Seed Default Vulnerability Assessments
        DB::table('vulnerability_assessments')->insert([
            [
                'region_id' => 1, // Mau Forest Complex
                'climate_dataset_id' => 1,
                'vegetation_dataset_id' => 2,
                'threshold_id' => 1,
                'generated_by' => 2,
                'temperature_score' => 28.50,
                'rainfall_score' => 35.20,
                'ndvi_score' => 20.40,
                'overall_score' => 28.03,
                'vulnerability_level' => 'Low',
                'interpretation' => 'Montane forest canopy continues to act as a robust microclimate buffer. High average NDVI values and consistent rainfall limit the current short-term climate stress risk levels.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'region_id' => 2, // Tana Delta
                'climate_dataset_id' => 1,
                'vegetation_dataset_id' => 2,
                'threshold_id' => 1,
                'generated_by' => 2,
                'temperature_score' => 58.00,
                'rainfall_score' => 42.50,
                'ndvi_score' => 48.90,
                'overall_score' => 49.80,
                'vulnerability_level' => 'Moderate',
                'interpretation' => 'Coastal delta regions show moderate levels of ecosystem stress. Siltation changes and seasonal precipitation variability introduce minor vegetative loss risk trends.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'region_id' => 3, // Mt. Kenya region
                'climate_dataset_id' => 1,
                'vegetation_dataset_id' => 2,
                'threshold_id' => 1,
                'generated_by' => 2,
                'temperature_score' => 18.20,
                'rainfall_score' => 22.10,
                'ndvi_score' => 15.40,
                'overall_score' => 18.57,
                'vulnerability_level' => 'Low',
                'interpretation' => 'High altitude vegetation layers and cold alpine conditions result in low overall climate vulnerability indicators, although long-term glacial recession metrics require periodic mapping updates.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'region_id' => 4, // Turkana
                'climate_dataset_id' => 1,
                'vegetation_dataset_id' => 2,
                'threshold_id' => 1,
                'generated_by' => 2,
                'temperature_score' => 92.50,
                'rainfall_score' => 88.40,
                'ndvi_score' => 84.10,
                'overall_score' => 88.33,
                'vulnerability_level' => 'High',
                'interpretation' => 'Severe ecological indicators are present. Prolonged dry spells and high temperature averages coupled with sparse semi-arid vegetation coverage trigger severe ecosystem degradation alert conditions.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
