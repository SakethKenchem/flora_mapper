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
            // Region 1: Mau Forest Complex
            [
                'flora_id' => 1,
                'region_id' => 1,
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
                'region_id' => 1,
                'scientific_name' => 'Podocarpus falcatus',
                'common_name' => 'East African Yellowwood',
                'species_type' => 'Tree',
                'conservation_status' => 'Vulnerable',
                'habitat_type' => 'Montane Forest',
                'vulnerability_level' => 'High',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'flora_id' => 3,
                'region_id' => 1,
                'scientific_name' => 'Juniperus procera',
                'common_name' => 'African Pencil Cedar',
                'species_type' => 'Tree',
                'conservation_status' => 'Near Threatened',
                'habitat_type' => 'Highland Forest',
                'vulnerability_level' => 'Moderate',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Region 2: Tana Delta
            [
                'flora_id' => 4,
                'region_id' => 2,
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
                'flora_id' => 5,
                'region_id' => 2,
                'scientific_name' => 'Avicennia marina',
                'common_name' => 'Grey Mangrove',
                'species_type' => 'Mangrove',
                'conservation_status' => 'Least Concern',
                'habitat_type' => 'Intertidal Zone',
                'vulnerability_level' => 'High',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'flora_id' => 6,
                'region_id' => 2,
                'scientific_name' => 'Suaeda monoica',
                'common_name' => 'Saltbird Bush',
                'species_type' => 'Shrub',
                'conservation_status' => 'Least Concern',
                'habitat_type' => 'Alkaline Swamps',
                'vulnerability_level' => 'Moderate',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Region 3: Mt. Kenya region
            [
                'flora_id' => 7,
                'region_id' => 3,
                'scientific_name' => 'Olea europaea subsp. cuspidata',
                'common_name' => 'African Wild Olive',
                'species_type' => 'Tree',
                'conservation_status' => 'Near Threatened',
                'habitat_type' => 'Montane Forest',
                'vulnerability_level' => 'Low',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'flora_id' => 8,
                'region_id' => 3,
                'scientific_name' => 'Hagenia abyssinica',
                'common_name' => 'East African Rosewood',
                'species_type' => 'Tree',
                'conservation_status' => 'Least Concern',
                'habitat_type' => 'Subalpine Belt',
                'vulnerability_level' => 'Low',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'flora_id' => 9,
                'region_id' => 3,
                'scientific_name' => 'Lobelia telekii',
                'common_name' => 'Giant Lobelia',
                'species_type' => 'Herb',
                'conservation_status' => 'Near Threatened',
                'habitat_type' => 'Alpine Moorlands',
                'vulnerability_level' => 'High',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Region 4: Turkana
            [
                'flora_id' => 10,
                'region_id' => 4,
                'scientific_name' => 'Acacia tortilis',
                'common_name' => 'Umbrella Thorn',
                'species_type' => 'Tree',
                'conservation_status' => 'Least Concern',
                'habitat_type' => 'Dry Savannah',
                'vulnerability_level' => 'Moderate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'flora_id' => 11,
                'region_id' => 4,
                'scientific_name' => 'Salvadora persica',
                'common_name' => 'Toothbrush Tree',
                'species_type' => 'Shrub',
                'conservation_status' => 'Least Concern',
                'habitat_type' => 'Sandy Stream Beds',
                'vulnerability_level' => 'Low',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'flora_id' => 12,
                'region_id' => 4,
                'scientific_name' => 'Hyphaene compressa',
                'common_name' => 'Doum Palm',
                'species_type' => 'Palm',
                'conservation_status' => 'Least Concern',
                'habitat_type' => 'Semi-Arid Oasis',
                'vulnerability_level' => 'Moderate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
