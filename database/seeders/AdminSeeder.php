<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Creates the default administrator account for managing the ISO 21001
     * Quality Education system for CSS (Computer System Servicing) students.
     */
    public function run(): void
    {
        $name = env('ADMIN_NAME', 'System Administrator');
        $username = env('ADMIN_USERNAME', 'admin');
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD', 'Admin@1');

        $admin = Admin::updateOrCreate(
            [
                'email' => $email,
            ],
            [
                'name' => $name,
                'username' => $username,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin account ensured.');
        $this->command->info("Username: {$username}");
        $this->command->info("Email: {$email}");
        $this->command->info("Password (set via env ADMIN_PASSWORD or default): {$password}");
        $this->command->warn('Please change the default password after first login.');
    }
}
