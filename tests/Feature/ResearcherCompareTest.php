<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResearcherCompareTest extends TestCase
{
    use RefreshDatabase;

    protected $researcher;
    protected $regionA;
    protected $regionB;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(\Database\Seeders\RoleSeeder::class);
        
        // Create user
        $this->researcher = User::create([
            'role_id' => 2,
            'full_name' => 'Jane Researcher',
            'email' => 'researcher@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);

        // Create regions
        $this->regionA = Region::create([
            'region_name' => 'Mau Forest Complex',
            'county' => 'Nakuru',
            'ecosystem_type' => 'Forest',
        ]);

        $this->regionB = Region::create([
            'region_name' => 'Tana Delta',
            'county' => 'Tana River',
            'ecosystem_type' => 'Wetland',
        ]);
    }

    public function test_researcher_can_access_compare_regions_dashboard_without_params(): void
    {
        $response = $this->actingAs($this->researcher)
            ->get(route('researcher.compare'));

        $response->assertStatus(200);
        $response->assertSee('Compare Regional Vulnerabilities');
        $response->assertSee('Select Region A from the dropdown list');
    }

    public function test_researcher_can_compare_two_selected_regions(): void
    {
        $response = $this->actingAs($this->researcher)
            ->get(route('researcher.compare', [
                'region_a' => $this->regionA->region_id,
                'region_b' => $this->regionB->region_id,
            ]));

        $response->assertStatus(200);
        $response->assertSee('Mau Forest Complex');
        $response->assertSee('Tana Delta');
    }

    public function test_researcher_can_download_comparison_report(): void
    {
        $response = $this->actingAs($this->researcher)
            ->get(route('researcher.compare.download', [
                'region_a' => $this->regionA->region_id,
                'region_b' => $this->regionB->region_id,
            ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="comparison_mau_forest_complex_vs_tana_delta.html"');
        $response->assertSee('Ecosystem Comparison Report');
        $response->assertSee('Mau Forest Complex');
        $response->assertSee('Tana Delta');
    }
}
