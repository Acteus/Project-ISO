<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\Log;

class ExportSurveyDataForTraining extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:survey-data
                            {--output= : Output file path (default: ai-service/data/existing_survey_data.csv)}
                            {--format=csv : Output format (csv or json)}
                            {--anonymize : Anonymize sensitive data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export survey data for AI model training';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Exporting Survey Data for AI Training');
        $this->line('=========================================');

        // Get all survey responses
        $responses = SurveyResponse::all();

        if ($responses->isEmpty()) {
            $this->warn('No survey responses found in database.');
            $this->line('Use synthetic data generation for training.');
            return 1;
        }

        $this->info("Found {$responses->count()} survey responses");

        // Prepare data for export
        $exportData = [];
        $anonymize = $this->option('anonymize');

        foreach ($responses as $response) {
            $data = [
                // Student Information
                'student_id' => $anonymize ? 'ANON-' . $response->id : $response->student_id,
                'track' => $response->track,
                'grade_level' => $response->grade_level,
                'academic_year' => $response->academic_year,
                'semester' => $response->semester,
                'gender' => $response->gender,

                // ISO 21001 Learner Needs Assessment
                'curriculum_relevance_rating' => $response->curriculum_relevance_rating,
                'learning_pace_appropriateness' => $response->learning_pace_appropriateness,
                'individual_support_availability' => $response->individual_support_availability,
                'learning_style_accommodation' => $response->learning_style_accommodation,

                // ISO 21001 Learner Satisfaction Metrics
                'teaching_quality_rating' => $response->teaching_quality_rating,
                'learning_environment_rating' => $response->learning_environment_rating,
                'peer_interaction_satisfaction' => $response->peer_interaction_satisfaction,
                'extracurricular_satisfaction' => $response->extracurricular_satisfaction,

                // ISO 21001 Learner Success Indicators
                'academic_progress_rating' => $response->academic_progress_rating,
                'skill_development_rating' => $response->skill_development_rating,
                'critical_thinking_improvement' => $response->critical_thinking_improvement,
                'problem_solving_confidence' => $response->problem_solving_confidence,

                // ISO 21001 Learner Safety Assessment
                'physical_safety_rating' => $response->physical_safety_rating,
                'psychological_safety_rating' => $response->psychological_safety_rating,
                'bullying_prevention_effectiveness' => $response->bullying_prevention_effectiveness,
                'emergency_preparedness_rating' => $response->emergency_preparedness_rating,

                // ISO 21001 Learner Wellbeing Metrics
                'mental_health_support_rating' => $response->mental_health_support_rating,
                'stress_management_support' => $response->stress_management_support,
                'physical_health_support' => $response->physical_health_support,
                'overall_wellbeing_rating' => $response->overall_wellbeing_rating,

                // Overall Satisfaction and Feedback
                'overall_satisfaction' => $response->overall_satisfaction,
                'positive_aspects' => $response->positive_aspects ?? '',
                'improvement_suggestions' => $response->improvement_suggestions ?? '',
                'additional_comments' => $response->additional_comments ?? '',

                // Privacy and Consent
                'consent_given' => $response->consent_given ? 1 : 0,
                'ip_address' => $anonymize ? '0.0.0.0' : ($response->ip_address ?? '0.0.0.0'),

                // Indirect Metrics
                'attendance_rate' => $response->attendance_rate ?? 80.0,
                'grade_average' => $response->grade_average ?? 3.0,
                'participation_score' => $response->participation_score ?? 3,
                'extracurricular_hours' => $response->extracurricular_hours ?? 0,
                'counseling_sessions' => $response->counseling_sessions ?? 0,

                // Timestamps
                'created_at' => $response->created_at->toIso8601String(),
                'updated_at' => $response->updated_at->toIso8601String(),
            ];

            $exportData[] = $data;
        }

        // Determine output path
        $format = $this->option('format');
        $outputPath = $this->option('output') ?? base_path("ai-service/data/existing_survey_data.{$format}");

        // Create directory if it doesn't exist
        $directory = dirname($outputPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
            $this->info("Created directory: {$directory}");
        }

        // Export data
        try {
            if ($format === 'json') {
                $this->exportJson($exportData, $outputPath);
            } else {
                $this->exportCsv($exportData, $outputPath);
            }

            $this->info("\n✅ Successfully exported {$responses->count()} responses");
            $this->line("   Output file: {$outputPath}");
            $this->line("   Format: {$format}");
            $this->line("   Anonymized: " . ($anonymize ? 'Yes' : 'No'));

            $this->line("\n📊 Next steps:");
            $this->line("   1. cd ai-service");
            $this->line("   2. python train_models.py");
            $this->line("   3. Restart Flask service to load trained models");

            Log::info('Survey data exported for training', [
                'count' => $responses->count(),
                'format' => $format,
                'output' => $outputPath
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error("Failed to export data: " . $e->getMessage());
            Log::error('Survey data export failed', ['error' => $e->getMessage()]);
            return 1;
        }
    }

    /**
     * Export data to CSV format
     */
    protected function exportCsv(array $data, string $path)
    {
        $fp = fopen($path, 'w');

        if ($fp === false) {
            throw new \Exception("Could not open file for writing: {$path}");
        }

        // Write header
        if (!empty($data)) {
            fputcsv($fp, array_keys($data[0]));
        }

        // Write data
        foreach ($data as $row) {
            fputcsv($fp, $row);
        }

        fclose($fp);
    }

    /**
     * Export data to JSON format
     */
    protected function exportJson(array $data, string $path)
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($json === false) {
            throw new \Exception("Failed to encode data to JSON");
        }

        if (file_put_contents($path, $json) === false) {
            throw new \Exception("Could not write to file: {$path}");
        }
    }
}
