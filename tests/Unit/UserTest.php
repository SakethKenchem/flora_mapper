<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_user_role_identification_helpers(): void
    {
        $admin = User::create([
            'role_id' => 3,
            'full_name' => 'Admin User',
            'email' => 'admin@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);

        $researcher = User::create([
            'role_id' => 2,
            'full_name' => 'Researcher User',
            'email' => 'researcher@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);

        $publicUser = User::create([
            'role_id' => 1,
            'full_name' => 'Public User',
            'email' => 'public@floramapper.com',
            'password' => bcrypt('Password123'),
            'account_status' => 'Active',
        ]);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isResearcher());

        $this->assertTrue($researcher->isResearcher());
        $this->assertFalse($researcher->isAdmin());

        $this->assertTrue($publicUser->isPublic());
        $this->assertFalse($publicUser->isResearcher());
    }
}
