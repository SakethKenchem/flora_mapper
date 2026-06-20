<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Flora;
use App\Models\ObservationReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ObservationReportTest extends TestCase
{
    use RefreshDatabase;

    protected $publicUser;
    protected $researcherUser;
    protected $testFlora;

    protected function setUp(): void
    {
        parent::setUp();
        
        Storage::fake('public');
        Storage::fake('local');

        // Seed Roles
        $this->seed(\Database\Seeders\RoleSeeder::class);

        // Create a test general public user
        $this->publicUser = User::create([
            'role_id' => 1, // GENERAL_PUBLIC
            'full_name' => 'John Public',
            'email' => 'public@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);

        // Create a test researcher user
        $this->researcherUser = User::create([
            'role_id' => 2, // RESEARCHER
            'full_name' => 'Dr. Jane Researcher',
            'email' => 'researcher@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);

        // Create a test flora record
        $this->testFlora = Flora::create([
            'scientific_name' => 'Ficus sycomorus',
            'common_name' => 'Sycamore Fig',
            'species_type' => 'Tree',
            'conservation_status' => 'Least Concern',
        ]);
    }

    public function test_unauthenticated_user_cannot_submit_observation(): void
    {
        $response = $this->post(route('public.observations.submit'), []);
        $response->assertRedirect(route('login'));
    }

    public function test_public_user_can_submit_valid_observation(): void
    {
        $image = UploadedFile::fake()->image('test_tree.jpg');
        $csv = UploadedFile::fake()->createWithContent('test_data.csv', "header1,header2\nval1,val2\nval3,val4");

        $response = $this->actingAs($this->publicUser)
            ->post(route('public.observations.submit'), [
                'flora_id' => $this->testFlora->flora_id,
                'location' => 'Lari Forest',
                'date_observed' => now()->toDateString(),
                'description' => 'Leaf discoloration observed on local species.',
                'image_file' => $image,
                'csv_file' => $csv,
                'temperature_celsius' => 24.5,
                'rainfall_mm' => 150.2,
                'humidity_percent' => 65.0,
                'drought_index' => 2.5,
                'ndvi_value' => 0.452,
                'vegetation_cover_percent' => 75.5,
                'vegetation_condition' => 'Healthy',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('observation_reports', [
            'public_id' => $this->publicUser->user_id,
            'flora_id' => $this->testFlora->flora_id,
            'flora_name' => 'Ficus sycomorus',
            'location' => 'Lari Forest',
            'description' => 'Leaf discoloration observed on local species.',
            'status' => 'Pending',
            'temperature_celsius' => 24.5,
            'rainfall_mm' => 150.20,
            'humidity_percent' => 65.00,
            'drought_index' => 2.50,
            'ndvi_value' => 0.452,
            'vegetation_cover_percent' => 75.50,
            'vegetation_condition' => 'Healthy',
        ]);

        $report = ObservationReport::where('location', 'Lari Forest')->first();
        $this->assertNotNull($report->image_path);
        $this->assertNotNull($report->csv_path);

        Storage::disk('public')->assertExists($report->image_path);
        Storage::disk('local')->assertExists($report->csv_path);
    }

    public function test_observation_submission_requires_compulsory_fields(): void
    {
        $response = $this->actingAs($this->publicUser)
            ->post(route('public.observations.submit'), []);

        $response->assertSessionHasErrors([
            'location',
            'date_observed',
            'description',
            'image_file',
            'csv_file',
        ]);
    }

    public function test_researcher_can_fetch_observation_details_and_parsed_csv(): void
    {
        Storage::disk('local')->put('observations/csvs/temp.csv', "measurement,value\ntemp,25\nrain,120");

        $report = ObservationReport::create([
            'public_id' => $this->publicUser->user_id,
            'flora_name' => 'Ficus sycomorus',
            'location' => 'Tana Delta',
            'description' => 'Discolored leaves',
            'image_path' => 'observations/images/temp.jpg',
            'csv_path' => 'observations/csvs/temp.csv',
            'temperature_celsius' => 24.5,
            'rainfall_mm' => 150.2,
            'humidity_percent' => 65.0,
            'drought_index' => 2.5,
            'ndvi_value' => 0.452,
            'vegetation_cover_percent' => 75.5,
            'vegetation_condition' => 'Healthy',
            'date_observed' => now()->toDateString(),
            'submission_date' => now(),
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($this->researcherUser)
            ->get(route('researcher.observations.details', $report->observation_id));

        $response->assertOk();
        $response->assertJsonStructure([
            'observation' => [
                'observation_id',
                'flora_name',
                'location',
                'description',
                'image_url',
                'temperature_celsius',
                'rainfall_mm',
                'humidity_percent',
                'drought_index',
                'ndvi_value',
                'vegetation_cover_percent',
                'vegetation_condition',
                'date_observed',
                'submission_date',
                'status',
                'observer',
            ],
            'csv_data' => [
                'headers',
                'rows',
            ],
        ]);

        $response->assertJsonFragment([
            'flora_name' => 'Ficus sycomorus',
            'location' => 'Tana Delta',
            'temperature_celsius' => 24.5,
            'rainfall_mm' => 150.2,
            'humidity_percent' => 65,
            'drought_index' => 2.5,
            'ndvi_value' => 0.452,
            'vegetation_cover_percent' => 75.5,
            'vegetation_condition' => 'Healthy',
        ]);

        $this->assertEquals(['measurement', 'value'], $response->json('csv_data.headers'));
        $this->assertEquals([['temp', '25'], ['rain', '120']], $response->json('csv_data.rows'));
    }

    public function test_researcher_can_review_observation(): void
    {
        $report = ObservationReport::create([
            'public_id' => $this->publicUser->user_id,
            'flora_name' => 'Rhizophora mucronata',
            'location' => 'Mida Creek',
            'description' => 'Fungal attack on roots',
            'date_observed' => now()->toDateString(),
            'submission_date' => now(),
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($this->researcherUser)
            ->post(route('researcher.observations.review', $report->observation_id), [
                'status' => 'Approved',
                'review_comment' => 'Confirmed fungal infection.',
            ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('observation_reports', [
            'observation_id' => $report->observation_id,
            'status' => 'Approved',
            'review_comment' => 'Confirmed fungal infection.',
            'researcher_id' => $this->researcherUser->user_id,
        ]);
    }
}
