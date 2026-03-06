<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Seed the roles table and create a default admin user.
     */
    public function run(): void
    {
        // Create the three application roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $teacher = Role::firstOrCreate(['name' => 'teacher']);
        $student = Role::firstOrCreate(['name' => 'student']);

        // Create a default admin user if none exists
        if (!User::where('email', 'admin@school.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@school.com',
                'password' => Hash::make('password'),
                'role_id' => $admin->id,
            ]);
        }
    }
}
