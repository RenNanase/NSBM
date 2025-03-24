<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update the admin user
        DB::table('users')
            ->where('username', 'admin')
            ->update(['is_admin' => true]);

        // Also set any other users with admin-like powers
        $this->command->info('Admin user updated successfully!');
    }
}
