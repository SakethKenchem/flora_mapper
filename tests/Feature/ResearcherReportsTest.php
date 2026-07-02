<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResearcherReportsTest extends TestCase
{
    use RefreshDatabase;

    protected $researcher;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(\Database\Seeders\RoleSeeder::class);
        
        $this->researcher = User::create([
            'role_id' => 2,
            'full_name' => 'Jane Researcher',
            'email' => 'researcher@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);
    }

    public function test_researcher_can_access_reports_page(): void
    {
        $response = $this->actingAs($this->researcher)
            ->get(route('researcher.reports'));

        $response->assertStatus(200);
        $response->assertSee('Reports Manager');
        $response->assertSee('Compile New Report');
    }

    public function test_researcher_can_generate_analytical_vulnerability_report(): void
    {
        $response = $this->actingAs($this->researcher)
            ->post(route('researcher.reports.generate'), [
                'report_title' => 'Ecosystem Vulnerability Analysis Summary Report',
                'report_type' => 'vulnerability_summary',
            ]);

        $response->assertRedirect(route('researcher.reports'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('reports', [
            'report_title' => 'Ecosystem Vulnerability Analysis Summary Report',
            'report_type' => 'Ecosystem Vulnerability Summary',
            'generated_by' => $this->researcher->user_id,
        ]);
    }
}
