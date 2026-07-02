<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Region;
use App\Models\ClimateData;
use App\Models\VegetationData;
use App\Models\Dataset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResearcherAnalysisTest extends TestCase
{
    use RefreshDatabase;

    protected $researcher;
    protected $region;
    protected $climateDataset;
    protected $vegetationDataset;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(\Database\Seeders\RoleSeeder::class);
        $this->seed(\Database\Seeders\RegionSeeder::class);
        $this->region = Region::first();

        $this->researcher = User::create([
            'role_id' => 2,
            'full_name' => 'Jane Researcher',
            'email' => 'researcher@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);

        // Create sample datasets
        $this->climateDataset = Dataset::create([
            'uploaded_by' => $this->researcher->user_id,
            'dataset_name' => 'Climate Ingestion',
            'dataset_type' => 'Climate',
            'source_name' => 'KMD',
            'upload_status' => 'Validated',
        ]);

        $this->vegetationDataset = Dataset::create([
            'uploaded_by' => $this->researcher->user_id,
            'dataset_name' => 'Vegetation Ingestion',
            'dataset_type' => 'Vegetation',
            'source_name' => 'MODIS',
            'upload_status' => 'Validated',
        ]);

        // Create climate records
        ClimateData::create([
            'dataset_id' => $this->climateDataset->dataset_id,
            'region_id' => $this->region->region_id,
            'record_date' => now()->toDateString(),
            'temperature_celsius' => 26.5,
            'rainfall_mm' => 150.0,
            'drought_index' => 3.5,
        ]);

        // Create vegetation records
        VegetationData::create([
            'dataset_id' => $this->vegetationDataset->dataset_id,
            'region_id' => $this->region->region_id,
            'record_date' => now()->toDateString(),
            'ndvi_value' => 0.450,
            'vegetation_cover_percent' => 70.0,
            'vegetation_condition' => 'Moderate',
        ]);
    }

    public function test_researcher_can_access_analysis_page(): void
    {
        $response = $this->actingAs($this->researcher)
            ->get(route('researcher.analysis'));

        $response->assertStatus(200);
        $response->assertSee('Vulnerability Analysis Console');
    }

    public function test_researcher_can_run_vulnerability_computation(): void
    {
        // Create Admin User for ThresholdSeeder bypassing fillable primary key guards
        \Illuminate\Support\Facades\DB::table('users')->insert([
            'user_id' => 3,
            'role_id' => 3,
            'full_name' => 'System Admin',
            'email' => 'admin@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed Thresholds
        $this->seed(\Database\Seeders\ThresholdSeeder::class);

        $response = $this->actingAs($this->researcher)
            ->post(route('researcher.analysis.submit'), [
                'region_id' => $this->region->region_id,
                'climate_dataset_id' => $this->climateDataset->dataset_id,
                'vegetation_dataset_id' => $this->vegetationDataset->dataset_id,
            ]);

        $response->assertRedirect(route('researcher.dashboard'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('vulnerability_assessments', [
            'region_id' => $this->region->region_id,
            'climate_dataset_id' => $this->climateDataset->dataset_id,
            'vegetation_dataset_id' => $this->vegetationDataset->dataset_id,
            'generated_by' => $this->researcher->user_id,
        ]);
    }
}
