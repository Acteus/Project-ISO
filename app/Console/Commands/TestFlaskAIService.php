<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlaskAIClient;
use App\Services\AIService;

class TestFlaskAIService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:test-flask
                            {--service-only : Test only Flask service availability}
                            {--compliance : Test compliance prediction}
                            {--sentiment : Test sentiment analysis}
                            {--clustering : Test student clustering}
                            {--performance : Test performance prediction}
                            {--dropout : Test dropout risk prediction}
                            {--risk : Test risk assessment}
                            {--trend : Test satisfaction trend analysis}
                            {--all : Test all AI models}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Flask AI service integration and fallback mechanisms';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Flask AI Service Integration');
        $this->line('=====================================');

        $flaskClient = app(FlaskAIClient::class);
        $aiService = app(AIService::class);

        // Test service availability
        $this->info('1. Testing Flask Service Availability...');
        $serviceStatus = $flaskClient->getServiceStatus();

        if ($serviceStatus['available']) {
            $this->info('✅ Flask AI service is available');
            $this->line('   URL: ' . $serviceStatus['base_url']);
            $this->line('   Last checked: ' . $serviceStatus['last_checked']);
        } else {
            $this->error('❌ Flask AI service is not available');
            $this->line('   URL: ' . $serviceStatus['base_url']);
            $this->warn('   System will use PHP fallback implementations');
        }

        if ($this->option('service-only')) {
            return;
        }

        // Determine if we should run all tests by default
        $runAllByDefault = !$this->option('compliance') && !$this->option('sentiment') &&
                          !$this->option('clustering') && !$this->option('performance') &&
                          !$this->option('dropout') && !$this->option('risk') && !$this->option('trend');

        // Test compliance prediction
        if ($this->option('compliance') || $runAllByDefault || $this->option('all')) {
            $this->info('2. Testing Compliance Prediction...');
            $testData = [
                'learner_needs_index' => 4.2,
                'satisfaction_score' => 3.8,
                'success_index' => 4.1,
                'safety_index' => 4.5,
                'wellbeing_index' => 3.9,
                'overall_satisfaction' => 4.0
            ];

            try {
                $startTime = microtime(true);
                $result = $aiService->predictCompliance($testData);
                $endTime = microtime(true);

                $this->info('✅ Compliance prediction successful');
                $this->line('   Model used: ' . ($result['model_used'] ?? 'Unknown'));
                $this->line('   Prediction: ' . $result['prediction']);
                $this->line('   Risk Level: ' . $result['risk_level']);
                $this->line('   Confidence: ' . $result['confidence']);
                $this->line('   Response time: ' . round(($endTime - $startTime) * 1000, 2) . 'ms');

                if (isset($result['analysis']['recommended_actions'])) {
                    $this->line('   Recommendations: ' . count($result['analysis']['recommended_actions']));
                }
            } catch (\Exception $e) {
                $this->error('❌ Compliance prediction failed: ' . $e->getMessage());
            }
        }

        // Test sentiment analysis
        if ($this->option('sentiment') || $runAllByDefault || $this->option('all')) {
            $this->info('3. Testing Sentiment Analysis...');
            $testComments = [
                "The teaching quality is excellent and very engaging!",
                "I feel supported in my learning journey.",
                "The curriculum could be more relevant to real-world applications.",
                "Great learning environment and helpful instructors."
            ];

            try {
                $startTime = microtime(true);
                $result = $aiService->analyzeSentiment($testComments);
                $endTime = microtime(true);

                $this->info('✅ Sentiment analysis successful');
                $this->line('   Model used: ' . ($result['model_used'] ?? 'Unknown'));
                $this->line('   Overall sentiment: ' . $result['overall_sentiment']);
                $this->line('   Sentiment score: ' . $result['sentiment_score'] . '/100');
                $this->line('   Comments analyzed: ' . $result['total_comments_analyzed']);
                $this->line('   Response time: ' . round(($endTime - $startTime) * 1000, 2) . 'ms');

                if (isset($result['breakdown'])) {
                    $this->line('   Breakdown - Positive: ' . $result['breakdown']['positive'] .
                                ', Neutral: ' . $result['breakdown']['neutral'] .
                                ', Negative: ' . $result['breakdown']['negative']);
                }
            } catch (\Exception $e) {
                $this->error('❌ Sentiment analysis failed: ' . $e->getMessage());
            }
        }

        // Test performance prediction
        if ($this->option('performance') || $runAllByDefault || $this->option('all')) {
            $this->info('4. Testing Performance Prediction...');
            $testData = [
                'curriculum_relevance_rating' => 4.2,
                'learning_pace_appropriateness' => 3.8,
                'teaching_quality_rating' => 4.5,
                'attendance_rate' => 85.5,
                'participation_score' => 4.2,
                'overall_satisfaction' => 4.0
            ];

            try {
                $startTime = microtime(true);
                $result = $aiService->predictPerformance($testData);
                $endTime = microtime(true);

                $this->info('✅ Performance prediction successful');
                $this->line('   Model used: ' . ($result['model_used'] ?? 'Unknown'));
                $this->line('   Prediction: ' . $result['prediction']);
                $this->line('   Predicted GPA: ' . $result['predicted_gpa']);
                $this->line('   Risk Level: ' . $result['risk_level']);
                $this->line('   Confidence: ' . $result['confidence']);
                $this->line('   Response time: ' . round(($endTime - $startTime) * 1000, 2) . 'ms');

                if (isset($result['recommendations'])) {
                    $this->line('   Recommendations: ' . count($result['recommendations']));
                }
            } catch (\Exception $e) {
                $this->error('❌ Performance prediction failed: ' . $e->getMessage());
            }
        }

        // Test dropout risk prediction
        if ($this->option('dropout') || $runAllByDefault || $this->option('all')) {
            $this->info('5. Testing Dropout Risk Prediction...');
            $testData = [
                'attendance_rate' => 65.2,
                'overall_satisfaction' => 2.8,
                'academic_progress_rating' => 2.5,
                'physical_safety_rating' => 3.2,
                'psychological_safety_rating' => 2.9,
                'mental_health_support_rating' => 2.7
            ];

            try {
                $startTime = microtime(true);
                $result = $aiService->predictDropoutRisk($testData);
                $endTime = microtime(true);

                $this->info('✅ Dropout risk prediction successful');
                $this->line('   Model used: ' . ($result['model_used'] ?? 'Unknown'));
                $this->line('   Risk Level: ' . $result['dropout_risk']);
                $this->line('   Risk Probability: ' . $result['risk_probability']);
                $this->line('   Intervention Urgency: ' . $result['intervention_urgency']);
                $this->line('   Confidence: ' . $result['confidence']);
                $this->line('   Response time: ' . round(($endTime - $startTime) * 1000, 2) . 'ms');

                if (isset($result['risk_factors'])) {
                    $this->line('   Risk Factors: ' . count($result['risk_factors']));
                }
            } catch (\Exception $e) {
                $this->error('❌ Dropout risk prediction failed: ' . $e->getMessage());
            }
        }

        // Test risk assessment
        if ($this->option('risk') || $runAllByDefault || $this->option('all')) {
            $this->info('6. Testing Risk Assessment...');
            $testData = [
                'curriculum_relevance_rating' => 3.8,
                'teaching_quality_rating' => 3.5,
                'physical_safety_rating' => 4.2,
                'mental_health_support_rating' => 3.1,
                'attendance_rate' => 78.5,
                'overall_satisfaction' => 3.6,
                'grade_average' => 2.8
            ];

            try {
                $startTime = microtime(true);
                $result = $aiService->assessRisk($testData);
                $endTime = microtime(true);

                $this->info('✅ Risk assessment successful');
                $this->line('   Model used: ' . ($result['model_used'] ?? 'Unknown'));
                $this->line('   Overall Risk Score: ' . $result['overall_risk_score']);
                $this->line('   Risk Level: ' . $result['risk_level']);
                $this->line('   Risk Category: ' . $result['risk_category']);
                $this->line('   Compliance Impact: ' . $result['compliance_impact']);
                $this->line('   Confidence: ' . $result['confidence']);
                $this->line('   Response time: ' . round(($endTime - $startTime) * 1000, 2) . 'ms');

                if (isset($result['primary_concerns'])) {
                    $this->line('   Primary Concerns: ' . count($result['primary_concerns']));
                }
            } catch (\Exception $e) {
                $this->error('❌ Risk assessment failed: ' . $e->getMessage());
            }
        }

        // Test satisfaction trend analysis
        if ($this->option('trend') || $runAllByDefault || $this->option('all')) {
            $this->info('7. Testing Satisfaction Trend Analysis...');
            $testData = [
                'curriculum_relevance_rating' => 4.2,
                'teaching_quality_rating' => 4.1,
                'learning_environment_rating' => 3.9,
                'overall_satisfaction' => 4.0,
                'timestamp' => '2024-01-15T10:00:00Z'
            ];

            try {
                $startTime = microtime(true);
                $result = $aiService->predictSatisfactionTrend($testData);
                $endTime = microtime(true);

                $this->info('✅ Satisfaction trend analysis successful');
                $this->line('   Model used: ' . ($result['model_used'] ?? 'Unknown'));
                $this->line('   Current Satisfaction: ' . $result['current_satisfaction']);
                $this->line('   Trend Direction: ' . $result['trend_direction']);
                $this->line('   Trend Strength: ' . $result['trend_strength']);
                $this->line('   Confidence: ' . $result['confidence']);
                $this->line('   Response time: ' . round(($endTime - $startTime) * 1000, 2) . 'ms');

                if (isset($result['forecasted_satisfaction'])) {
                    $this->line('   Forecast Periods: ' . count($result['forecasted_satisfaction']));
                }
            } catch (\Exception $e) {
                $this->error('❌ Satisfaction trend analysis failed: ' . $e->getMessage());
            }
        }

        // Test clustering (would need actual survey data)
        if ($this->option('clustering')) {
            $this->info('8. Testing Student Clustering...');
            $this->warn('   Note: Clustering test requires actual survey data in database');
            $this->line('   Skipping clustering test - use with real data');
        }

        $this->line('');
        $this->info('AI Service testing completed!');
        $this->line('Use --help to see available test options');
    }
}
