<?php
// database/seeders/AdminUserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Faculty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin (can access all faculties)
        User::updateOrCreate(
            ['email' => 'admin@skalafa.unib.ac.id'],
            [
                'name' => 'Super Administrator SKALAFA',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'faculty_id' => null, // Super admin has no specific faculty
            ]
        );

        $this->command->info('✓ Super Admin created: admin@skalafa.unib.ac.id');

        // Get all faculties
        $faculties = Faculty::all();

        if ($faculties->isEmpty()) {
            $this->command->warn('⚠ No faculties found. Please run FacultySeeder first.');
            return;
        }

        // Create Faculty Admins for each faculty
        foreach ($faculties as $faculty) {
            // Generate email from faculty name
            $emailSlug = strtolower(str_replace(' ', '.', $faculty->short_name));
            $email = "admin.{$emailSlug}@skalafa.unib.ac.id";

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => "Admin {$faculty->name}",
                    'email_verified_at' => now(),
                    'password' => Hash::make('admin123'), // Same password for all faculty admins
                    'role' => 'faculty_admin',
                    'faculty_id' => $faculty->id,
                ]
            );

            $this->command->info("✓ Faculty Admin created: {$email} for {$faculty->name}");
        }

        $this->command->info("\n✅ All admin users created successfully!");
        $this->command->info("📧 Default password for all admins: admin123");
    }
}
