<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Region;
use App\Models\Flora;
use App\Models\Dataset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FloraRegistryTest extends TestCase
{
    use RefreshDatabase;

    protected $researcherUser;
    protected $generalUser;
    protected $region;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        // Seed Roles
        $this->seed(\Database\Seeders\RoleSeeder::class);

        // Seed Regions
        $this->seed(\Database\Seeders\RegionSeeder::class);
        $this->region = Region::first();

        // Create researcher user
        $this->researcherUser = User::create([
            'role_id' => 2, // RESEARCHER
            'full_name' => 'Dr. Jane Researcher',
            'email' => 'researcher@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);

        // Create general public user
        $this->generalUser = User::create([
            'role_id' => 1, // GENERAL_PUBLIC
            'full_name' => 'John Public',
            'email' => 'public@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);
    }

    public function test_guest_cannot_access_flora_registry(): void
    {
        $response = $this->get(route('researcher.flora.manage'));
        $response->assertRedirect(route('login'));
    }

    public function test_general_public_cannot_access_flora_registry(): void
    {
        $response = $this->actingAs($this->generalUser)
            ->get(route('researcher.flora.manage'));
        $response->assertRedirect(route('public.dashboard'));
    }

    public function test_researcher_can_access_flora_registry(): void
    {
        $response = $this->actingAs($this->researcherUser)
            ->get(route('researcher.flora.manage'));
        
        $response->assertStatus(200);
        $response->assertSee('Flora Registry');
        $response->assertSee('Add Flora Species Manually');
        $response->assertSee('Bulk CSV Flora Dataset Upload');
    }

    public function test_researcher_can_manually_add_flora(): void
    {
        $response = $this->actingAs($this->researcherUser)
            ->post(route('researcher.flora.store'), [
                'scientific_name' => 'Acacia tortilis',
                'common_name' => 'Umbrella Thorn',
                'region_id' => $this->region->region_id,
                'species_type' => 'Tree',
                'conservation_status' => 'Least Concern',
                'habitat_type' => 'Savannah',
                'vulnerability_level' => 'Moderate',
            ]);

        $response->assertRedirect(route('researcher.dashboard'));
        $response->assertSessionHasNoErrors();
        
        $this->assertDatabaseHas('flora', [
            'scientific_name' => 'Acacia tortilis',
            'common_name' => 'Umbrella Thorn',
            'region_id' => $this->region->region_id,
            'vulnerability_level' => 'Moderate',
        ]);
    }

    public function test_researcher_cannot_manually_add_flora_with_duplicate_scientific_name(): void
    {
        Flora::create([
            'scientific_name' => 'Acacia tortilis',
            'common_name' => 'Umbrella Thorn',
            'region_id' => $this->region->region_id,
            'vulnerability_level' => 'Moderate',
        ]);

        $response = $this->actingAs($this->researcherUser)
            ->post(route('researcher.flora.store'), [
                'scientific_name' => 'Acacia tortilis',
                'common_name' => 'Another Umbrella Thorn',
                'region_id' => $this->region->region_id,
                'species_type' => 'Tree',
                'conservation_status' => 'Least Concern',
                'habitat_type' => 'Savannah',
                'vulnerability_level' => 'Moderate',
            ]);

        $response->assertSessionHasErrors(['scientific_name']);
    }

    public function test_researcher_can_bulk_upload_flora_csv(): void
    {
        $csvContent = "region_name,scientific_name,common_name,species_type,conservation_status,habitat_type,vulnerability_level\n" .
            "{$this->region->region_name},Senegalia senegal,Gum Acacia,Tree,Least Concern,Arid scrubland,Moderate";

        $csvFile = UploadedFile::fake()->createWithContent('flora_test.csv', $csvContent);

        $response = $this->actingAs($this->researcherUser)
            ->post(route('researcher.datasets.flora.upload.submit'), [
                'dataset_name' => 'Test Flora Bulk Dataset',
                'source_name' => 'GBIF',
                'description' => 'Test CSV Flora Import',
                'csv_file' => $csvFile,
            ]);

        $response->assertRedirect(route('researcher.dashboard'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('datasets', [
            'dataset_name' => 'Test Flora Bulk Dataset',
            'dataset_type' => 'Flora',
            'upload_status' => 'Validated',
        ]);

        $this->assertDatabaseHas('flora', [
            'scientific_name' => 'Senegalia senegal',
            'common_name' => 'Gum Acacia',
            'region_id' => $this->region->region_id,
            'vulnerability_level' => 'Moderate',
        ]);
    }
}
