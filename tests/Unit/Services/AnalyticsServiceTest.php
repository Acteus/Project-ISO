<?php

namespace Tests\Unit\Services;

use App\Models\SurveyResponse;
use App\Services\AnalyticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AnalyticsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AnalyticsService();
    }

    public function test_get_analytics_summary_with_empty_data()
    {
        $result = $this->service->getAnalyticsSummary();

        $this->assertFalse($result['has_data']);
        $this->assertEquals(0, $result['total_responses']);
        $this->assertStringContainsString('No survey responses', $result['message']);
    }

    public function test_get_analytics_summary_with_data()
    {
        SurveyResponse::factory()->count(5)->create([
            'track' => 'CSS',
            'grade_level' => 11,
            'overall_satisfaction' => 4,
            'consent_given' => true,
        ]);

        $result = $this->service->getAnalyticsSummary();

        $this->assertTrue($result['has_data']);
        $this->assertEquals(5, $result['total_responses']);
        $this->assertArrayHasKey('iso_indices', $result);
        $this->assertArrayHasKey('overall', $result);
        $this->assertArrayHasKey('distribution', $result);
    }

    public function test_get_analytics_summary_filters_by_track()
    {
        SurveyResponse::factory()->count(3)->create(['track' => 'CSS', 'consent_given' => true]);
        SurveyResponse::factory()->count(2)->create(['track' => 'STEM', 'consent_given' => true]);

        $result = $this->service->getAnalyticsSummary(['track' => 'CSS']);

        $this->assertEquals(3, $result['total_responses']);
    }

    public function test_get_analytics_summary_calculates_iso_indices()
    {
        SurveyResponse::factory()->create([
            'curriculum_relevance_rating' => 4,
            'learning_pace_appropriateness' => 4,
            'individual_support_availability' => 4,
            'learning_style_accommodation' => 4,
            'teaching_quality_rating' => 5,
            'learning_environment_rating' => 5,
            'peer_interaction_satisfaction' => 5,
            'extracurricular_satisfaction' => 5,
            'overall_satisfaction' => 4,
            'consent_given' => true,
        ]);

        $result = $this->service->getAnalyticsSummary();

        $this->assertEquals(4.0, $result['iso_indices']['learner_needs']);
        $this->assertEquals(5.0, $result['iso_indices']['satisfaction']);
    }

    public function test_get_analytics_summary_calculates_overall_satisfaction()
    {
        SurveyResponse::factory()->create([
            'overall_satisfaction' => 5,
            'consent_given' => true,
        ]);
        SurveyResponse::factory()->create([
            'overall_satisfaction' => 3,
            'consent_given' => true,
        ]);

        $result = $this->service->getAnalyticsSummary();

        $this->assertEquals(4.0, $result['overall']['satisfaction']);
    }

    public function test_get_analytics_summary_includes_distribution()
    {
        SurveyResponse::factory()->create([
            'grade_level' => 11,
            'gender' => 'Male',
            'semester' => '1st',
            'consent_given' => true,
        ]);

        $result = $this->service->getAnalyticsSummary();

        $this->assertArrayHasKey('by_grade', $result['distribution']);
        $this->assertArrayHasKey('by_gender', $result['distribution']);
        $this->assertArrayHasKey('by_semester', $result['distribution']);
    }

    public function test_get_analytics_summary_includes_compliance()
    {
        SurveyResponse::factory()->count(3)->create([
            'overall_satisfaction' => 4,
            'consent_given' => true,
        ]);

        $result = $this->service->getAnalyticsSummary();

        $this->assertArrayHasKey('compliance', $result);
        $this->assertArrayHasKey('score', $result['compliance']);
        $this->assertArrayHasKey('level', $result['compliance']);
    }
}

