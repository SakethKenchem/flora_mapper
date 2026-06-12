<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Roles
        $this->call(RoleSeeder::class);

        // Seed System Administrator
        User::create([
            'role_id' => 3, // SYSTEM_ADMINISTRATOR
            'full_name' => 'System Administrator',
            'email' => 'admin@floramapper.com',
            'password' => Hash::make('Password123'),
            'phone_number' => '+254712345678',
            'account_status' => 'Active',
        ]);

        // Seed Researcher
        User::create([
            'role_id' => 2, // RESEARCHER
            'full_name' => 'Dr. Jane Mwangi',
            'email' => 'researcher@floramapper.com',
            'password' => Hash::make('Password123'),
            'phone_number' => '+254723456789',
            'institution' => 'Kenya Forestry Research Institute (KEFRI)',
            'account_status' => 'Active',
        ]);

        // Seed General Public User
        User::create([
            'role_id' => 1, // GENERAL_PUBLIC
            'full_name' => 'John Doe',
            'email' => 'public@floramapper.com',
            'password' => Hash::make('Password123'),
            'phone_number' => '+254734567890',
            'account_status' => 'Active',
        ]);

        // Seed regions and default rules
        $this->call(RegionSeeder::class);
        $this->call(ThresholdSeeder::class);
    }
}
