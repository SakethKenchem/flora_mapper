<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_guest_can_register_as_general_public(): void
    {
        $response = $this->post(route('register.submit'), [
            'full_name' => 'John Public',
            'email' => 'public@floramapper.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'phone_number' => '+254711223344',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'full_name' => 'John Public',
            'email' => 'public@floramapper.com',
            'role_id' => 1, // GENERAL_PUBLIC
            'account_status' => 'Active',
        ]);
    }

    public function test_guest_can_apply_as_researcher_with_pending_status(): void
    {
        $response = $this->post(route('register.researcher.submit'), [
            'full_name' => 'Dr. Alice Mwangi',
            'email' => 'alice@kefri.org',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'phone_number' => '+254722334455',
            'institution' => 'KEFRI',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'full_name' => 'Dr. Alice Mwangi',
            'email' => 'alice@kefri.org',
            'role_id' => 2, // RESEARCHER
            'account_status' => 'Pending',
            'institution' => 'KEFRI',
        ]);
    }

    public function test_login_authenticates_and_redirects_active_users(): void
    {
        $user = User::create([
            'role_id' => 1,
            'full_name' => 'Active Observer',
            'email' => 'observer@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'observer@floramapper.com',
            'password' => 'Password123',
        ]);

        $response->assertRedirect(route('public.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_rejects_pending_or_suspended_users(): void
    {
        User::create([
            'role_id' => 2,
            'full_name' => 'Pending Researcher',
            'email' => 'pending@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Pending',
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'pending@floramapper.com',
            'password' => 'Password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}
