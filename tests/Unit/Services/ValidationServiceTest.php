<?php

namespace Tests\Unit\Services;

use App\Models\SurveyResponse;
use App\Services\ValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ValidationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ValidationService();
    }

    public function test_validate_direct_vs_indirect_with_empty_data()
    {
        $result = $this->service->validateDirectVsIndirect();

        $this->assertEquals('No data available for validation analysis', $result['message']);
        $this->assertEquals(0, $result['total_responses']);
        $this->assertEquals(0, $result['validation_score']);
    }

    public function test_validate_direct_vs_indirect_detects_high_satisfaction_low_performance()
    {
        SurveyResponse::factory()->create([
            'overall_satisfaction' => 5,
            'grade_average' => 2.0,
            'attendance_rate' => 70,
            'consent_given' => true,
        ]);

        $result = $this->service->validateDirectVsIndirect();

        $this->assertGreaterThan(0, $result['total_responses']);
        $this->assertNotEmpty($result['discrepancies']);
        
        $discrepancy = collect($result['discrepancies'])->firstWhere('type', 'HIGH_SATISFACTION_LOW_PERFORMANCE');
        $this->assertNotNull($discrepancy);
        $this->assertEquals('HIGH', $discrepancy['severity']);
    }

    public function test_validate_direct_vs_indirect_filters_by_track()
    {
        SurveyResponse::factory()->create([
            'track' => 'CSS',
            'overall_satisfaction' => 5,
            'consent_given' => true,
        ]);

        SurveyResponse::factory()->create([
            'track' => 'CSS',
            'overall_satisfaction' => 5,
            'consent_given' => true,
        ]);

        $result = $this->service->validateDirectVsIndirect('CSS');

        $this->assertEquals(2, $result['total_responses']);
    }

    public function test_validate_accessibility_compliance()
    {
        SurveyResponse::factory()->create([
            'learning_style_accommodation' => 1,
            'individual_support_availability' => 2,
            'learning_pace_appropriateness' => 1,
            'consent_given' => true,
            'track' => 'CSS',
        ]);

        $result = $this->service->validateAccessibilityCompliance('CSS');

        $this->assertEquals(1, $result['total_responses']);
        $this->assertNotEmpty($result['issues']);
        $this->assertLessThan(100, $result['accessibility_score']);
    }

    public function test_validate_data_quality()
    {
        SurveyResponse::factory()->create([
            'consent_given' => false,
            'track' => 'CSS',
        ]);

        SurveyResponse::factory()->create([
            'consent_given' => true,
            'track' => 'CSS',
        ]);

        $result = $this->service->validateDataQuality('CSS');

        $this->assertEquals(2, $result['total_responses']);
        $this->assertNotEmpty($result['issues']);
        
        $consentIssue = collect($result['issues'])->firstWhere('type', 'MISSING_CONSENT');
        $this->assertNotNull($consentIssue);
    }

    public function test_generate_comprehensive_compliance_report()
    {
        SurveyResponse::factory()->count(5)->create([
            'overall_satisfaction' => 4,
            'grade_average' => 3.5,
            'consent_given' => true,
            'track' => 'CSS',
        ]);

        $result = $this->service->generateComprehensiveComplianceReport('CSS');

        $this->assertTrue($result['comprehensive_report']);
        $this->assertArrayHasKey('overall_compliance', $result);
        $this->assertArrayHasKey('recommendations', $result);
        $this->assertArrayHasKey('direct_vs_indirect', $result);
        $this->assertArrayHasKey('accessibility', $result);
        $this->assertArrayHasKey('data_quality', $result);
    }
}

