<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ObservationReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResearcherReviewObservationsTest extends TestCase
{
    use RefreshDatabase;

    protected $researcher;
    protected $publicUser;
    protected $observation;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->researcher = User::create([
            'role_id' => 2,
            'full_name' => 'Dr. Jane Researcher',
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

        // Create observation report
        $this->observation = ObservationReport::create([
            'public_id' => $this->publicUser->user_id,
            'flora_name' => 'Rhizophora mucronata',
            'location' => 'Mau Forest Complex',
            'description' => 'Test observation details.',
            'date_observed' => now()->toDateString(),
            'submission_date' => now(),
            'status' => 'Pending',
        ]);
    }

    public function test_researcher_can_view_observation_review_modal_json(): void
    {
        $response = $this->actingAs($this->researcher)
            ->get(route('researcher.observations.details', $this->observation->observation_id));

        $response->assertStatus(200);
        $response->assertJsonFragment(['flora_name' => 'Rhizophora mucronata']);
    }

    public function test_researcher_can_access_observation_review_page(): void
    {
        $response = $this->actingAs($this->researcher)
            ->get(route('researcher.observations.review.show', $this->observation->observation_id));

        $response->assertStatus(200);
        $response->assertSee('Review Public Observation');
        $response->assertSee('Rhizophora mucronata');
    }

    public function test_researcher_can_approve_observation_report(): void
    {
        $response = $this->actingAs($this->researcher)
            ->post(route('researcher.observations.review', $this->observation->observation_id), [
                'status' => 'Approved',
                'review_comment' => 'Validated by field logs',
            ]);

        $response->assertRedirect(route('researcher.dashboard'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('observation_reports', [
            'observation_id' => $this->observation->observation_id,
            'status' => 'Approved',
            'review_comment' => 'Validated by field logs',
            'researcher_id' => $this->researcher->user_id,
        ]);
    }

    public function test_researcher_can_reject_observation_report(): void
    {
        $response = $this->actingAs($this->researcher)
            ->post(route('researcher.observations.review', $this->observation->observation_id), [
                'status' => 'Rejected',
                'review_comment' => 'Incorrect species classification',
            ]);

        $response->assertRedirect(route('researcher.dashboard'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('observation_reports', [
            'observation_id' => $this->observation->observation_id,
            'status' => 'Rejected',
            'review_comment' => 'Incorrect species classification',
            'researcher_id' => $this->researcher->user_id,
        ]);
    }
}
