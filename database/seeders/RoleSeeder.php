<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'role_id' => 1,
                'role_name' => 'GENERAL_PUBLIC',
                'description' => 'General public user who can view maps and submit observations.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'role_name' => 'RESEARCHER',
                'description' => 'Researcher who manages environmental datasets and performs assessments.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'role_name' => 'SYSTEM_ADMINISTRATOR',
                'description' => 'Administrator who manages accounts and vulnerability thresholds.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
