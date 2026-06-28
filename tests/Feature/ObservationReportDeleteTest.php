<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Flora;
use App\Models\ObservationReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ObservationReportDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $ownerUser;
    protected $otherUser;
    protected $observation;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('local');

        // Seed Roles
        $this->seed(\Database\Seeders\RoleSeeder::class);

        // Create owner public user
        $this->ownerUser = User::create([
            'role_id' => 1, // GENERAL_PUBLIC
            'full_name' => 'Owner Observer',
            'email' => 'owner@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);

        // Create other public user
        $this->otherUser = User::create([
            'role_id' => 1, // GENERAL_PUBLIC
            'full_name' => 'Other Observer',
            'email' => 'other@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);

        // Store fake files
        $imagePath = UploadedFile::fake()->image('tree.jpg')->store('observations/images', 'public');
        $csvPath = UploadedFile::fake()->create('data.csv')->store('observations/csvs');

        // Create observation report
        $this->observation = ObservationReport::create([
            'public_id' => $this->ownerUser->user_id,
            'flora_name' => 'Rhizophora mucronata',
            'location' => 'Mau Forest Complex',
            'description' => 'Test observation',
            'image_path' => $imagePath,
            'csv_path' => $csvPath,
            'date_observed' => now()->toDateString(),
            'submission_date' => now(),
            'status' => 'Pending',
        ]);
    }

    public function test_guest_cannot_delete_observation(): void
    {
        $response = $this->post(route('public.observations.delete', $this->observation->observation_id));
        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('observation_reports', [
            'observation_id' => $this->observation->observation_id,
        ]);
    }

    public function test_unauthorized_user_cannot_delete_observation(): void
    {
        $response = $this->actingAs($this->otherUser)
            ->post(route('public.observations.delete', $this->observation->observation_id));

        $response->assertStatus(403);

        $this->assertDatabaseHas('observation_reports', [
            'observation_id' => $this->observation->observation_id,
        ]);
    }

    public function test_owner_can_delete_observation_deletes_files_and_record(): void
    {
        $imagePath = $this->observation->image_path;
        $csvPath = $this->observation->csv_path;

        // Verify files exist initially
        Storage::disk('public')->assertExists($imagePath);
        Storage::disk('local')->assertExists($csvPath);

        $response = $this->actingAs($this->ownerUser)
            ->post(route('public.observations.delete', $this->observation->observation_id));

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        // Verify database record is deleted
        $this->assertDatabaseMissing('observation_reports', [
            'observation_id' => $this->observation->observation_id,
        ]);

        // Verify files are deleted
        Storage::disk('public')->assertMissing($imagePath);
        Storage::disk('local')->assertMissing($csvPath);
    }
}
