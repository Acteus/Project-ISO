<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * This will seed essential data for the application.
     * Uncomment the CssSurveyResponseSeeder to add demo/test data for CSS students.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,

            // Uncomment the line below to seed sample CSS student survey responses for testing/demo
            // CssSurveyResponseSeeder::class,
        ]);
    }
}
