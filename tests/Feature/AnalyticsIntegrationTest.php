<?php

namespace Tests\Feature;

use App\Models\SurveyResponse;
use App\Models\User;
use App\Services\AnalyticsService;
use App\Services\ValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_analytics_endpoint_returns_data()
    {
        SurveyResponse::factory()->count(5)->create([
            'track' => 'CSS',
            'overall_satisfaction' => 4,
            'consent_given' => true,
        ]);

        $user = User::factory()->create(['role' => 'admin']);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/survey/analytics?track=CSS');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'total_responses',
                        'iso_21001_indices',
                    ]
                ]);
    }

    public function test_analytics_service_integration()
    {
        SurveyResponse::factory()->count(10)->create([
            'track' => 'CSS',
            'overall_satisfaction' => 4,
            'consent_given' => true,
        ]);

        $service = new AnalyticsService();
        $result = $service->getAnalyticsSummary(['track' => 'CSS']);

        $this->assertTrue($result['has_data']);
        $this->assertEquals(10, $result['total_responses']);
        $this->assertArrayHasKey('iso_indices', $result);
    }

    public function test_validation_service_integration()
    {
        SurveyResponse::factory()->create([
            'track' => 'CSS',
            'overall_satisfaction' => 5,
            'grade_average' => 2.0,
            'consent_given' => true,
        ]);

        $service = new ValidationService();
        $result = $service->validateDirectVsIndirect('CSS');

        $this->assertEquals(1, $result['total_responses']);
        $this->assertNotEmpty($result['discrepancies']);
    }

    public function test_comprehensive_validation_integration()
    {
        SurveyResponse::factory()->count(5)->create([
            'track' => 'CSS',
            'overall_satisfaction' => 4,
            'consent_given' => true,
        ]);

        $service = new ValidationService();
        $result = $service->generateComprehensiveComplianceReport('CSS');

        $this->assertTrue($result['comprehensive_report']);
        $this->assertArrayHasKey('overall_compliance', $result);
        $this->assertArrayHasKey('recommendations', $result);
    }
}


