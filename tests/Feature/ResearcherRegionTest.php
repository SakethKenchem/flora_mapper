<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResearcherRegionTest extends TestCase
{
    use RefreshDatabase;

    protected $researcher;
    protected $publicUser;
    protected $region;

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

        $this->publicUser = User::create([
            'role_id' => 1,
            'full_name' => 'John Public',
            'email' => 'public@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);
    }

    public function test_guest_cannot_access_edit_region(): void
    {
        $response = $this->get(route('researcher.regions.edit', $this->region->region_id));
        $response->assertRedirect(route('login'));
    }

    public function test_public_user_cannot_access_edit_region(): void
    {
        $response = $this->actingAs($this->publicUser)
            ->get(route('researcher.regions.edit', $this->region->region_id));
        $response->assertRedirect(route('public.dashboard'));
    }

    public function test_researcher_can_access_edit_region(): void
    {
        $response = $this->actingAs($this->researcher)
            ->get(route('researcher.regions.edit', $this->region->region_id));

        $response->assertStatus(200);
        $response->assertSee('Edit Region Details');
        $response->assertSee($this->region->region_name);
    }

    public function test_researcher_can_update_region_details(): void
    {
        $response = $this->actingAs($this->researcher)
            ->post(route('researcher.regions.update', $this->region->region_id), [
                'region_name' => 'Mau Forest Block Updated',
                'county' => 'Nakuru',
                'ecosystem_type' => 'Highland Forest',
                'latitude' => -0.65,
                'longitude' => 35.85,
                'description' => 'Updated description details.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('regions', [
            'region_id' => $this->region->region_id,
            'region_name' => 'Mau Forest Block Updated',
            'county' => 'Nakuru',
            'ecosystem_type' => 'Highland Forest',
            'latitude' => -0.65,
            'longitude' => 35.85,
        ]);
    }
}
