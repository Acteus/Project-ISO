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
        // Use updateOrCreate to avoid duplicate entry errors
        // This will update the password if the admin already exists
        Admin::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Kwadra Team Admin',
                'email' => 'kwadrateam@gmail.com',
                'password' => Hash::make('Admin@1'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin account created/updated successfully.');
        $this->command->info('Email: kwadrateam@gmail.com');
        $this->command->info('Username: admin');
        $this->command->info('Password: Admin@1');
    }
}
