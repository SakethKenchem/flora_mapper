<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ObservationReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('observation_reports')->insert([
            [
                'observation_id' => 1,
                'public_id' => 3, // John Doe
                'researcher_id' => null,
                'flora_id' => null,
                'flora_name' => 'Ficus sycomorus',
                'location' => 'Mau Forest Block B',
                'description' => 'Large sycamore fig tree showing signs of canopy thinning and yellowing leaves.',
                'image_path' => null,
                'date_observed' => '2026-06-08',
                'submission_date' => now(),
                'status' => 'Pending',
                'review_comment' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'observation_id' => 2,
                'public_id' => 3, // John Doe
                'researcher_id' => null,
                'flora_id' => null,
                'flora_name' => 'Rhizophora mucronata',
                'location' => 'Tana Delta wetland swamp',
                'description' => 'Mangrove roots showing black spots, possible fungal attack due to salinity shifts.',
                'image_path' => null,
                'date_observed' => '2026-06-10',
                'submission_date' => now(),
                'status' => 'Pending',
                'review_comment' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'observation_id' => 3,
                'public_id' => 3, // John Doe
                'researcher_id' => null,
                'flora_id' => null,
                'flora_name' => 'Olea europaea',
                'location' => 'Mt. Kenya Naro Moru trail',
                'description' => 'Healthy cluster of African wild olives growing near the trail path.',
                'image_path' => null,
                'date_observed' => '2026-06-11',
                'submission_date' => now(),
                'status' => 'Pending',
                'review_comment' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
