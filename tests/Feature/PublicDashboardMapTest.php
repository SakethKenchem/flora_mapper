<?php

namespace Tests\Feature;

use App\Models\Region;
use App\Models\Flora;
use App\Models\ClimateData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicDashboardMapTest extends TestCase
{
    use RefreshDatabase;

    protected $region;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\RegionSeeder::class);
        $this->region = Region::first();
    }

    public function test_public_user_can_access_map_page(): void
    {
        $response = $this->get(route('map'));
        $response->assertStatus(200);
        $response->assertSee('Ecosystem Vulnerability Map');
    }

    public function test_vulnerability_data_endpoint_returns_json(): void
    {
        $response = $this->get(route('api.vulnerability_data'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'region_id',
                'region_name',
                'latitude',
                'longitude',
                'overall_score',
                'vulnerability_level',
            ]
        ]);
    }

    public function test_region_details_endpoint_returns_nested_records(): void
    {
        // Create user
        $user = \App\Models\User::create([
            'role_id' => 2,
            'full_name' => 'Jane Researcher',
            'email' => 'researcher@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);

        // Create dataset
        $dataset = \App\Models\Dataset::create([
            'uploaded_by' => $user->user_id,
            'dataset_name' => 'Climate Data Ingestion',
            'dataset_type' => 'Climate',
            'source_name' => 'KMD',
            'upload_status' => 'Validated',
        ]);

        // Add sample flora
        $flora = Flora::create([
            'region_id' => $this->region->region_id,
            'scientific_name' => 'Ficus sycomorus',
            'common_name' => 'Sycamore Fig',
            'vulnerability_level' => 'Low',
        ]);

        // Add sample climate record
        ClimateData::create([
            'dataset_id' => $dataset->dataset_id,
            'region_id' => $this->region->region_id,
            'record_date' => now()->toDateString(),
            'temperature_celsius' => 24.5,
            'rainfall_mm' => 120.0,
            'drought_index' => 1.5,
        ]);

        $response = $this->get(route('api.region_details', $this->region->region_id));
        $response->assertStatus(200);
        $response->assertJsonFragment(['region_name' => $this->region->region_name]);
        $response->assertJsonFragment(['scientific_name' => 'Ficus sycomorus']);
        $response->assertJsonFragment(['temperature_celsius' => 24.5]);
    }
}
