<?php

namespace Database\Seeders;

use App\Models\SurveyResponse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CssSurveyResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Enhanced seeder for AI Insights Testing
     * 
     * Creates comprehensive survey response data with:
     * - Diverse student profiles (high performers, average, struggling)
     * - Varied sentiment in comments for sentiment analysis
     * - Realistic data distributions for clustering
     * - Time-based data for trend analysis
     * - All ISO 21001 fields properly populated
     * - Data optimized for AI insights dashboard
     *
     * Survey Structure (21 ISO 21001 rating questions):
     * - Section 1: Learner Needs & Expectations (4 fields)
     * - Section 2: Teaching & Learning Quality (4 fields)
     * - Section 3: Learner Success Indicators (4 fields)
     * - Section 4: Learner Safety Assessment (4 fields)
     * - Section 5: Learner Wellbeing Metrics (4 fields)
     * - Section 6: Overall Satisfaction (1 field)
     * - Section 7: Additional Feedback Fields (3 fields)
     * - Section 8: Qualitative Feedback (3 text fields)
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting enhanced CSS survey response seeder for AI Insights...');
        $this->command->newLine();

        // Clear existing data (optional - comment out if you want to keep existing data)
        // SurveyResponse::where('track', 'CSS')->delete();

        // Create diverse dataset optimized for AI analysis
        $totalCount = 150; // Increased for better AI analysis
        $currentYear = date('Y');
        $academicYear = $currentYear . '-' . ($currentYear + 1);

        // Create high-performing students (30% of data)
        $this->command->info('📊 Creating high-performing student responses...');
        SurveyResponse::factory()
            ->count((int)($totalCount * 0.30))
            ->highPerformer()
            ->create([
                'track' => 'CSS',
                'academic_year' => $academicYear,
            ]);

        // Create average students (50% of data)
        $this->command->info('📊 Creating average student responses...');
        SurveyResponse::factory()
            ->count((int)($totalCount * 0.50))
            ->create([
                'track' => 'CSS',
                'academic_year' => $academicYear,
            ]);

        // Create struggling students (20% of data)
        $this->command->info('📊 Creating struggling student responses...');
        SurveyResponse::factory()
            ->count((int)($totalCount * 0.20))
            ->struggling()
            ->create([
                'track' => 'CSS',
                'academic_year' => $academicYear,
            ]);

        // Create time-series data with trends (for trend analysis)
        $this->command->info('📈 Creating time-series data for trend analysis...');
        $this->createTimeSeriesData($academicYear, 30);

        // Create data with varied comments for sentiment analysis
        $this->command->info('💬 Ensuring diverse comments for sentiment analysis...');
        $this->ensureDiverseComments();

        // Summary statistics
        $totalResponses = SurveyResponse::where('track', 'CSS')->count();
        $withComments = SurveyResponse::where('track', 'CSS')
            ->where(function($q) {
                $q->whereNotNull('positive_aspects')
                  ->orWhereNotNull('improvement_suggestions')
                  ->orWhereNotNull('additional_comments');
            })
            ->count();
        
        $avgSatisfaction = SurveyResponse::where('track', 'CSS')->avg('overall_satisfaction');
        $avgAttendance = SurveyResponse::where('track', 'CSS')->avg('attendance_rate');
        $avgGrade = SurveyResponse::where('track', 'CSS')->avg('grade_average');

        $this->command->newLine();
        $this->command->info('✅ Seeder completed successfully!');
        $this->command->newLine();
        $this->command->table(
            ['Metric', 'Value'],
            [
                ['Total Responses', $totalResponses],
                ['Responses with Comments', $withComments],
                ['Average Satisfaction', number_format($avgSatisfaction, 2) . '/5.0'],
                ['Average Attendance', number_format($avgAttendance, 1) . '%'],
                ['Average Grade', number_format($avgGrade, 2) . '/4.0'],
            ]
        );
        $this->command->newLine();
        $this->command->info('🎯 Data is now ready for AI Insights analysis!');
        $this->command->info('   - Compliance Prediction: ✅ Ready');
        $this->command->info('   - Sentiment Analysis: ✅ Ready (' . $withComments . ' comments)');
        $this->command->info('   - Student Clustering: ✅ Ready (' . $totalResponses . ' students)');
        $this->command->info('   - Trend Analysis: ✅ Ready (180-day distribution)');
        $this->command->info('   - Performance Prediction: ✅ Ready');
        $this->command->info('   - Risk Assessment: ✅ Ready');
    }

    /**
     * Create time-series data with trends for trend analysis
     */
    protected function createTimeSeriesData(string $academicYear, int $count): void
    {
        $baseDate = Carbon::now()->subDays(180);
        
        // Create data with improving trend
        for ($i = 0; $i < $count / 3; $i++) {
            $daysAgo = 180 - ($i * 6); // Spread over 180 days
            $date = Carbon::now()->subDays($daysAgo);
            
            // Improving trend: earlier dates have lower satisfaction
            $satisfactionBase = 3.0 + ($i * 0.05); // Gradually improving
            $satisfaction = min(5, max(1, (int)round($satisfactionBase + fake()->randomFloat(0, -0.5, 0.5))));
            
            SurveyResponse::factory()->create([
                'track' => 'CSS',
                'academic_year' => $academicYear,
                'overall_satisfaction' => $satisfaction,
                'teaching_quality_rating' => $satisfaction,
                'curriculum_relevance_rating' => $satisfaction,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // Create data with declining trend
        for ($i = 0; $i < $count / 3; $i++) {
            $daysAgo = 180 - ($i * 6);
            $date = Carbon::now()->subDays($daysAgo);
            
            // Declining trend: earlier dates have higher satisfaction
            $satisfactionBase = 4.5 - ($i * 0.05); // Gradually declining
            $satisfaction = min(5, max(1, (int)round($satisfactionBase + fake()->randomFloat(0, -0.5, 0.5))));
            
            SurveyResponse::factory()->create([
                'track' => 'CSS',
                'academic_year' => $academicYear,
                'overall_satisfaction' => $satisfaction,
                'teaching_quality_rating' => $satisfaction,
                'curriculum_relevance_rating' => $satisfaction,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        // Create data with stable trend
        for ($i = 0; $i < $count / 3; $i++) {
            $daysAgo = 180 - ($i * 6);
            $date = Carbon::now()->subDays($daysAgo);
            
            // Stable trend: consistent satisfaction
            $satisfaction = fake()->randomElement([3, 4, 4, 4, 4]);
            
            SurveyResponse::factory()->create([
                'track' => 'CSS',
                'academic_year' => $academicYear,
                'overall_satisfaction' => $satisfaction,
                'teaching_quality_rating' => $satisfaction,
                'curriculum_relevance_rating' => $satisfaction,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }

    /**
     * Ensure diverse comments for sentiment analysis
     */
    protected function ensureDiverseComments(): void
    {
        // Get responses without comments
        $responsesWithoutComments = SurveyResponse::where('track', 'CSS')
            ->whereNull('positive_aspects')
            ->whereNull('improvement_suggestions')
            ->whereNull('additional_comments')
            ->limit(20)
            ->get();

        // If no responses without comments, skip
        if ($responsesWithoutComments->isEmpty()) {
            return;
        }

        $positiveComments = [
            'The CSS program is excellent! The instructors are very knowledgeable and supportive.',
            'I love the hands-on training. The facilities are modern and well-maintained.',
            'Great program! I feel well-prepared for my future career in IT.',
            'The curriculum is comprehensive and relevant to real-world applications.',
            'Excellent learning environment. Teachers are always willing to help.',
        ];

        $negativeComments = [
            'The program is challenging and I need more support to understand the concepts.',
            'Some topics are difficult to grasp. More tutorial sessions would be helpful.',
            'I struggle with the pace of learning. Need more practice time.',
            'The curriculum is complex. Additional help would be beneficial.',
            'Finding it difficult to keep up with the coursework.',
        ];

        $neutralComments = [
            'The program is okay. Some improvements would make it better.',
            'Decent learning environment. The program meets my basic expectations.',
            'The program is good overall, but there is room for improvement.',
            'Satisfied with my progress, but more support would be beneficial.',
            'The program is adequate. Some topics could be explained better.',
        ];

        foreach ($responsesWithoutComments as $response) {
            $satisfaction = $response->overall_satisfaction;
            
            if ($satisfaction >= 4) {
                $response->update([
                    'positive_aspects' => fake()->randomElement($positiveComments),
                    'improvement_suggestions' => 'More advanced topics would be great.',
                    'additional_comments' => 'Overall, I am very satisfied with the program.',
                ]);
            } elseif ($satisfaction <= 2) {
                $response->update([
                    'positive_aspects' => 'Some instructors are helpful.',
                    'improvement_suggestions' => fake()->randomElement($negativeComments),
                    'additional_comments' => 'I need more support to succeed in this program.',
                ]);
            } else {
                $response->update([
                    'positive_aspects' => 'The program is decent.',
                    'improvement_suggestions' => fake()->randomElement($neutralComments),
                    'additional_comments' => 'The program is okay, but could be improved.',
                ]);
            }
        }
    }
}

