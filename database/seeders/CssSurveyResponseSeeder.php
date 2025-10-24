<?php

namespace Database\Seeders;

use App\Models\SurveyResponse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CssSurveyResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder creates sample survey responses specifically for CSS (Computer System Servicing) students
     * to populate the admin dashboard with demo data.
     *
     * Survey Structure (21 questions matching form.blade.php):
     * - Section 1: Learner Needs & Expectations (q1-q3)
     * - Section 2: Teaching & Learning Quality (q4-q6)
     * - Section 3: Assessments & Outcomes (q7-q9)
     * - Section 4: Support & Resources (q10-q12)
     * - Section 5: Environment & Inclusivity (q13-q15)
     * - Section 6: Feedback & Responsiveness (q16-q18)
     * - Section 7: Overall Satisfaction (q19-q21)
     * - Section 8: Demographics & Open-ended Questions
     */
    public function run(): void
    {
        // Create sample CSS student survey responses for testing
        // The factory generates responses that match the actual survey form structure
        SurveyResponse::factory()
            ->count(50)
            ->create([
                'track' => 'CSS', // Computer System Servicing track
            ]);

        $this->command->info('✓ Created 50 CSS student survey responses matching the survey form structure.');
    }
}

