<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Flora;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSearchTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $region;
    protected $flora;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed Roles
        $this->seed(\Database\Seeders\RoleSeeder::class);

        // Create user
        $this->user = User::create([
            'role_id' => 1, // GENERAL_PUBLIC
            'full_name' => 'John Public',
            'email' => 'public@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);

        // Create a region
        $this->region = Region::create([
            'region_name' => 'Mau Forest Complex',
            'county' => 'Nakuru',
            'ecosystem_type' => 'Montane Forest',
            'latitude' => -0.6,
            'longitude' => 35.8,
            'description' => 'Mau complex forest.',
        ]);

        // Create a flora species linked to that region
        $this->flora = Flora::create([
            'region_id' => $this->region->region_id,
            'scientific_name' => 'Ficus sycomorus',
            'common_name' => 'Sycamore Fig',
            'species_type' => 'Tree',
            'conservation_status' => 'Least Concern',
            'habitat_type' => 'Woodland',
            'vulnerability_level' => 'Low',
        ]);
    }

    public function test_guest_cannot_access_search_page(): void
    {
        $response = $this->get(route('public.search'));
        $response->assertRedirect(route('login'));
    }

    public function test_public_user_can_access_search_page_without_query(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('public.search'));

        $response->assertStatus(200);
        $response->assertSee('Search Registry');
        $response->assertSee('Enter keywords above to search for ecosystem regions');
    }

    public function test_public_user_can_search_by_keyword_and_see_results(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('public.search', ['q' => 'Mau']));

        $response->assertStatus(200);
        $response->assertSee('Matched Ecosystem Regions (1)');
        $response->assertSee('Mau Forest Complex');
        $response->assertSee('Nakuru');
    }

    public function test_public_user_can_search_flora_by_keyword_and_see_results(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('public.search', ['q' => 'Ficus']));

        $response->assertStatus(200);
        $response->assertSee('Matched Flora Species (1)');
        $response->assertSee('Ficus sycomorus');
        $response->assertSee('Sycamore Fig');
    }

    public function test_public_user_sees_no_results_message_when_no_matches_exist(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('public.search', ['q' => 'DesertPlantsNonExistent']));

        $response->assertStatus(200);
        $response->assertSee('No matching regions found.');
        $response->assertSee('No matching flora species found.');
    }
}
