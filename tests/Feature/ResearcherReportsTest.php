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

    public function test_researcher_can_download_compiled_report(): void
    {
        $report = Report::create([
            'generated_by' => $this->researcher->user_id,
            'report_title' => 'Sample Assessment Report',
            'report_type' => 'Ecosystem Vulnerability Summary',
            'content' => '<p>Report Body Content</p>'
        ]);

        $response = $this->actingAs($this->researcher)
            ->get(route('researcher.reports.download', $report->report_id));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $response->assertHeader('Content-Disposition', 'attachment; filename="sample_assessment_report.html"');
        $response->assertSee('Sample Assessment Report');
        $response->assertSee('Report Body Content');
    }
}
