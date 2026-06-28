<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $researcherUser;
    protected $publicUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed Roles
        $this->seed(\Database\Seeders\RoleSeeder::class);

        // Create Admin
        $this->adminUser = User::create([
            'role_id' => 3, // SYSTEM_ADMINISTRATOR
            'full_name' => 'Admin User',
            'email' => 'admin@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);

        // Create Researcher
        $this->researcherUser = User::create([
            'role_id' => 2, // RESEARCHER
            'full_name' => 'Dr. Jane Researcher',
            'email' => 'researcher@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
            'institution' => 'KEFRI',
        ]);

        // Create General Public
        $this->publicUser = User::create([
            'role_id' => 1, // GENERAL_PUBLIC
            'full_name' => 'John Observer',
            'email' => 'public@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);
    }

    public function test_guest_cannot_access_edit_user_page(): void
    {
        $response = $this->get(route('admin.users.edit', $this->researcherUser->user_id));
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_edit_user_page(): void
    {
        $response = $this->actingAs($this->publicUser)
            ->get(route('admin.users.edit', $this->researcherUser->user_id));
        
        $response->assertRedirect(route('public.dashboard'));
    }

    public function test_admin_can_access_edit_user_page(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.users.edit', $this->researcherUser->user_id));

        $response->assertStatus(200);
        $response->assertSee('Edit User Details');
        $response->assertSee($this->researcherUser->full_name);
    }

    public function test_admin_can_update_user_details(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.users.update', $this->researcherUser->user_id), [
                'full_name' => 'Dr. Jane Updated',
                'email' => 'jane_new@floramapper.com',
                'phone_number' => '+254700000000',
                'role_id' => 2, // keep researcher
                'account_status' => 'Suspended',
                'institution' => 'Kenya Forestry Research Institute',
                'specialisation' => 'Forest Ecology',
            ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'user_id' => $this->researcherUser->user_id,
            'full_name' => 'Dr. Jane Updated',
            'email' => 'jane_new@floramapper.com',
            'phone_number' => '+254700000000',
            'account_status' => 'Suspended',
            'institution' => 'Kenya Forestry Research Institute',
            'specialisation' => 'Forest Ecology',
        ]);
    }

    public function test_admin_cannot_update_own_role_or_status(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.users.update', $this->adminUser->user_id), [
                'full_name' => 'Admin Updated Name',
                'email' => 'admin_updated@floramapper.com',
                'phone_number' => '123456789',
                'role_id' => 1, // Attempt to demote to Public
                'account_status' => 'Suspended', // Attempt to suspend self
            ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'user_id' => $this->adminUser->user_id,
            'full_name' => 'Admin Updated Name',
            'email' => 'admin_updated@floramapper.com',
            'role_id' => 3, // Still System Admin
            'account_status' => 'Active', // Still Active
        ]);
    }

    public function test_validation_rejects_duplicate_email(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.users.update', $this->researcherUser->user_id), [
                'full_name' => 'Jane Duplicate',
                'email' => 'public@floramapper.com', // Duplicate of publicUser email
                'role_id' => 2,
                'account_status' => 'Active',
                'institution' => 'KEFRI',
            ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_validation_requires_institution_for_researcher_role(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.users.update', $this->publicUser->user_id), [
                'full_name' => 'Observer Promoted',
                'email' => 'public_promoted@floramapper.com',
                'role_id' => 2, // Promote to Researcher
                'account_status' => 'Active',
                'institution' => '', // Leave institution blank
            ]);

        $response->assertSessionHasErrors(['institution']);
    }
}
