<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FloraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('flora')->insert([
            [
                'flora_id' => 1,
                'region_id' => 1, // Mau Forest
                'scientific_name' => 'Ficus sycomorus',
                'common_name' => 'Sycamore Fig',
                'species_type' => 'Tree',
                'conservation_status' => 'Least Concern',
                'habitat_type' => 'Riverine Forest',
                'vulnerability_level' => 'Moderate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'flora_id' => 2,
                'region_id' => 2, // Tana Delta
                'scientific_name' => 'Rhizophora mucronata',
                'common_name' => 'Loop-root Mangrove',
                'species_type' => 'Mangrove',
                'conservation_status' => 'Least Concern',
                'habitat_type' => 'Coastal Swamps',
                'vulnerability_level' => 'High',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'flora_id' => 3,
                'region_id' => 3, // Mt. Kenya Region
                'scientific_name' => 'Olea europaea subsp. cuspidata',
                'common_name' => 'African Wild Olive',
                'species_type' => 'Tree',
                'conservation_status' => 'Near Threatened',
                'habitat_type' => 'Montane Forest',
                'vulnerability_level' => 'Low',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
