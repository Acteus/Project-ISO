<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FlaskAIClient;
use App\Services\AIService;

class TestAIServiceConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:test-connection
                            {--service= : Test specific service (flask|laravel)}
                            {--endpoint= : Test specific endpoint}
                            {--detailed : Show detailed output}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test AI service connectivity and functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing AI Service Connection');
        $this->info('================================');

        $service = $this->option('service') ?: 'all';
        $endpoint = $this->option('endpoint');
        $verbose = $this->option('detailed');

        $flaskClient = app(FlaskAIClient::class);
        $aiService = app(AIService::class);

        $results = [];

        // Test Flask Service Availability
        if ($service === 'all' || $service === 'flask') {
            $this->info("\n1. Testing Flask AI Service Availability...");
            $status = $flaskClient->getServiceStatus();

            if ($status['available']) {
                $this->info('✅ Flask AI service is available');
                $this->line("   URL: {$status['base_url']}");
                $this->line("   Timeout: {$status['timeout']}s");
                $this->line("   Cache: " . ($status['cache_enabled'] ? 'enabled' : 'disabled'));
                $results['flask_available'] = true;
            } else {
                $this->error('❌ Flask AI service is not available');
                $this->line("   URL: {$status['base_url']}");
                $results['flask_available'] = false;
            }
        }

        // Test Specific Endpoints
        if ($endpoint) {
            $this->info("\n2. Testing Specific Endpoint: {$endpoint}");

            $testData = $this->getTestDataForEndpoint($endpoint);

            try {
                $result = $this->testEndpoint($flaskClient, $endpoint, $testData);

                if ($result) {
                    $this->info("✅ Endpoint {$endpoint} is working");
                    if ($verbose) {
                        $this->line('Response: ' . json_encode($result, JSON_PRETTY_PRINT));
                    }
                    $results['endpoint_test'] = true;
                } else {
                    $this->error("❌ Endpoint {$endpoint} failed");
                    $results['endpoint_test'] = false;
                }
            } catch (\Exception $e) {
                $this->error("❌ Endpoint {$endpoint} error: {$e->getMessage()}");
                $results['endpoint_test'] = false;
            }
        }

        // Test Laravel AI Service Integration
        if ($service === 'all' || $service === 'laravel') {
            $this->info("\n3. Testing Laravel AI Service Integration...");

            $testData = [
                'learner_needs_index' => 4.2,
                'satisfaction_score' => 3.8,
                'success_index' => 4.1,
                'safety_index' => 4.5,
                'wellbeing_index' => 3.9,
                'overall_satisfaction' => 4.0
            ];

            try {
                $result = $aiService->predictCompliance($testData);

                if ($result && isset($result['prediction'])) {
                    $this->info('✅ Laravel AI service integration working');
                    $this->line("   Model Used: " . ($result['model_used'] ?? 'Unknown'));
                    $this->line("   Prediction: {$result['prediction']}");
                    $this->line("   Confidence: " . ($result['confidence'] ?? 'N/A'));
                    $results['laravel_integration'] = true;
                } else {
                    $this->error('❌ Laravel AI service integration failed');
                    $results['laravel_integration'] = false;
                }
            } catch (\Exception $e) {
                $this->error("❌ Laravel AI service error: {$e->getMessage()}");
                $results['laravel_integration'] = false;
            }
        }

        // Test All AI Models
        if ($service === 'all') {
            $this->info("\n4. Testing All AI Models...");

            $models = [
                'compliance' => 'Compliance Prediction',
                'sentiment' => 'Sentiment Analysis',
                'cluster' => 'Student Clustering',
                'performance' => 'Performance Prediction',
                'dropout' => 'Dropout Risk Prediction',
                'risk' => 'Risk Assessment',
                'trend' => 'Satisfaction Trend'
            ];

            foreach ($models as $modelKey => $modelName) {
                try {
                    $testResult = $this->testAIModel($aiService, $modelKey);

                    if ($testResult['success']) {
                        $this->info("✅ {$modelName}: Working");
                        if ($verbose) {
                            $this->line("   Details: " . json_encode($testResult['data']));
                        }
                    } else {
                        $this->warn("⚠️  {$modelName}: {$testResult['message']}");
                    }

                    $results['models'][$modelKey] = $testResult;
                } catch (\Exception $e) {
                    $this->error("❌ {$modelName}: Error - {$e->getMessage()}");
                    $results['models'][$modelKey] = ['success' => false, 'error' => $e->getMessage()];
                }
            }
        }

        // Summary
        $this->info("\n📊 Test Summary");
        $this->info('===============');

        $totalTests = count($results);
        $passedTests = count(array_filter($results, function($result) {
            return is_bool($result) ? $result : (isset($result['success']) ? $result['success'] : false);
        }));

        $this->line("Tests Passed: {$passedTests}/{$totalTests}");

        if ($passedTests === $totalTests) {
            $this->info('🎉 All tests passed! AI service is ready for production.');
        } else {
            $this->warn('⚠️  Some tests failed. Check configuration and service status.');
        }

        return $passedTests === $totalTests ? 0 : 1;
    }

    private function getTestDataForEndpoint($endpoint)
    {
        $testData = [
            'compliance' => [
                'learner_needs_index' => 4.2,
                'satisfaction_score' => 3.8,
                'success_index' => 4.1,
                'safety_index' => 4.5,
                'wellbeing_index' => 3.9,
                'overall_satisfaction' => 4.0
            ],
            'sentiment' => [
                'comments' => ['Great teaching quality!', 'Could improve support services']
            ],
            'cluster' => [
                'responses' => [
                    ['overall_satisfaction' => 4.0, 'attendance_rate' => 85],
                    ['overall_satisfaction' => 3.5, 'attendance_rate' => 75],
                    ['overall_satisfaction' => 2.8, 'attendance_rate' => 65]
                ],
                'clusters' => 2
            ]
        ];

        return $testData[$endpoint] ?? [];
    }

    private function testEndpoint($client, $endpoint, $data)
    {
        switch ($endpoint) {
            case 'compliance':
                return $client->predictCompliance($data);
            case 'sentiment':
                return $client->analyzeSentiment($data['comments']);
            case 'cluster':
                return $client->clusterStudents($data['responses'], $data['clusters']);
            case 'performance':
                return $client->predictPerformance($data);
            case 'dropout':
                return $client->predictDropoutRisk($data);
            case 'risk':
                return $client->assessRisk($data);
            case 'trend':
                return $client->predictSatisfactionTrend($data);
            default:
                return null;
        }
    }

    private function testAIModel($aiService, $model)
    {
        $testData = [
            'learner_needs_index' => 4.2,
            'satisfaction_score' => 3.8,
            'success_index' => 4.1,
            'safety_index' => 4.5,
            'wellbeing_index' => 3.9,
            'overall_satisfaction' => 4.0,
            'attendance_rate' => 85,
            'participation_score' => 4.0,
            'academic_progress_rating' => 4.1,
            'physical_safety_rating' => 4.5,
            'psychological_safety_rating' => 4.2,
            'mental_health_support_rating' => 4.0
        ];

        switch ($model) {
            case 'compliance':
                $result = $aiService->predictCompliance($testData);
                return [
                    'success' => isset($result['prediction']),
                    'data' => $result,
                    'message' => isset($result['prediction']) ? 'Compliance prediction successful' : 'Compliance prediction failed'
                ];

            case 'sentiment':
                $result = $aiService->analyzeSentiment(['Excellent course!', 'Good experience']);
                return [
                    'success' => isset($result['overall_sentiment']),
                    'data' => $result,
                    'message' => isset($result['overall_sentiment']) ? 'Sentiment analysis successful' : 'Sentiment analysis failed'
                ];

            case 'cluster':
                $responses = collect([
                    (object)['curriculum_relevance_rating' => 4.0, 'overall_satisfaction' => 4.2],
                    (object)['curriculum_relevance_rating' => 3.5, 'overall_satisfaction' => 3.8],
                ]);
                $result = $aiService->clusterResponses($responses, 2);
                return [
                    'success' => isset($result['num_clusters']),
                    'data' => $result,
                    'message' => isset($result['num_clusters']) ? 'Clustering successful' : 'Clustering failed'
                ];

            case 'performance':
                $result = $aiService->predictPerformance($testData);
                return [
                    'success' => isset($result['predicted_gpa']),
                    'data' => $result,
                    'message' => isset($result['predicted_gpa']) ? 'Performance prediction successful' : 'Performance prediction failed'
                ];

            case 'dropout':
                $result = $aiService->predictDropoutRisk($testData);
                return [
                    'success' => isset($result['dropout_risk']),
                    'data' => $result,
                    'message' => isset($result['dropout_risk']) ? 'Dropout risk prediction successful' : 'Dropout risk prediction failed'
                ];

            case 'risk':
                $result = $aiService->assessRisk($testData);
                return [
                    'success' => isset($result['overall_risk_score']),
                    'data' => $result,
                    'message' => isset($result['overall_risk_score']) ? 'Risk assessment successful' : 'Risk assessment failed'
                ];

            case 'trend':
                $result = $aiService->predictSatisfactionTrend($testData);
                return [
                    'success' => isset($result['trend_direction']),
                    'data' => $result,
                    'message' => isset($result['trend_direction']) ? 'Trend prediction successful' : 'Trend prediction failed'
                ];

            default:
                return [
                    'success' => false,
                    'message' => 'Unknown model type'
                ];
        }
    }
}
