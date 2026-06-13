<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Region;
use App\Models\Dataset;
use App\Models\Flora;
use Database\Seeders\RoleSeeder;
use Database\Seeders\RegionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FloraUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed roles and regions
        $this->seed(RoleSeeder::class);
        $this->seed(RegionSeeder::class);
    }

    public function test_guest_cannot_access_flora_upload_page()
    {
        $response = $this->get(route('researcher.datasets.flora.upload'));
        $response->assertRedirect(route('login'));
    }

    public function test_general_public_cannot_access_flora_upload_page()
    {
        $user = User::create([
            'role_id' => 1, // GENERAL_PUBLIC
            'full_name' => 'John Public',
            'email' => 'public@test.com',
            'password' => bcrypt('password'),
            'account_status' => 'Active',
        ]);

        $response = $this->actingAs($user)->get(route('researcher.datasets.flora.upload'));
        $response->assertRedirect(route('public.dashboard'));
    }

    public function test_researcher_can_access_flora_upload_page()
    {
        $user = User::create([
            'role_id' => 2, // RESEARCHER
            'full_name' => 'Jane Researcher',
            'email' => 'researcher@test.com',
            'password' => bcrypt('password'),
            'account_status' => 'Active',
        ]);

        $response = $this->actingAs($user)->get(route('researcher.datasets.flora.upload'));
        $response->assertStatus(200);
        $response->assertSee('Upload Flora Dataset');
    }

    public function test_researcher_can_upload_valid_flora_csv()
    {
        $user = User::create([
            'role_id' => 2, // RESEARCHER
            'full_name' => 'Jane Researcher',
            'email' => 'researcher@test.com',
            'password' => bcrypt('password'),
            'account_status' => 'Active',
        ]);

        // Create a fake CSV file
        $csvContent = "region_name,scientific_name,common_name,species_type,conservation_status,habitat_type,vulnerability_level\n" .
                      "Mau Forest,Ficus sycomorus,Sycamore Fig,Tree,Least Concern,Montane Forest,Low\n" .
                      "Tana Delta,Rhizophora mucronata,Red Mangrove,Mangrove,Near Threatened,Estuary/Mangrove,High\n" .
                      "Invalid Region Name,Olea europaea,African Wild Olive,Tree,Vulnerable,Alpine Forest,Moderate\n";

        $file = UploadedFile::fake()->createWithContent('flora_test.csv', $csvContent);

        $response = $this->actingAs($user)->post(route('researcher.datasets.flora.upload.submit'), [
            'dataset_name' => 'Test Flora Survey',
            'source_name' => 'KEFRI',
            'description' => 'A survey test description',
            'csv_file' => $file,
        ]);

        $response->assertRedirect(route('researcher.dashboard'));
        $response->assertSessionHas('success');

        // Assert dataset metadata was created
        $this->assertDatabaseHas('datasets', [
            'dataset_name' => 'Test Flora Survey',
            'dataset_type' => 'Flora',
            'source_name' => 'KEFRI',
        ]);

        $dataset = Dataset::where('dataset_name', 'Test Flora Survey')->first();

        // Assert flora records were created
        $this->assertDatabaseHas('flora', [
            'dataset_id' => $dataset->dataset_id,
            'region_id' => 1, // Mau Forest region_id
            'scientific_name' => 'Ficus sycomorus',
            'common_name' => 'Sycamore Fig',
            'species_type' => 'Tree',
            'conservation_status' => 'Least Concern',
            'habitat_type' => 'Montane Forest',
            'vulnerability_level' => 'Low',
        ]);

        $this->assertDatabaseHas('flora', [
            'dataset_id' => $dataset->dataset_id,
            'region_id' => 2, // Tana Delta region_id
            'scientific_name' => 'Rhizophora mucronata',
            'common_name' => 'Red Mangrove',
            'vulnerability_level' => 'High',
        ]);

        // Assert flora record with invalid region has null region_id but is still saved
        $this->assertDatabaseHas('flora', [
            'dataset_id' => $dataset->dataset_id,
            'region_id' => null,
            'scientific_name' => 'Olea europaea',
            'vulnerability_level' => 'Moderate',
        ]);

        $this->assertEquals(3, Flora::count());
    }
}
