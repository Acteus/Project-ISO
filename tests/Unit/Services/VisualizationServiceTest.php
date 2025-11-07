<?php

namespace Tests\Unit\Services;

use App\Models\SurveyResponse;
use App\Services\VisualizationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisualizationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected VisualizationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new VisualizationService();
    }

    public function test_generate_bar_chart_data_with_empty_data()
    {
        $result = $this->service->generateBarChartData('NONEXISTENT_TRACK');

        $this->assertArrayHasKey('labels', $result);
        $this->assertArrayHasKey('datasets', $result);
        $this->assertIsArray($result['datasets'][0]['data']);
    }

    public function test_generate_bar_chart_data_with_data()
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
            'track' => 'CSS',
        ]);

        $result = $this->service->generateBarChartData('CSS');

        $this->assertArrayHasKey('labels', $result);
        $this->assertArrayHasKey('datasets', $result);
        $this->assertNotEmpty($result['datasets'][0]['data']);
    }

    public function test_generate_pie_chart_data_with_empty_data()
    {
        $result = $this->service->generatePieChartData('NONEXISTENT_TRACK');

        $this->assertArrayHasKey('labels', $result);
        $this->assertArrayHasKey('datasets', $result);
    }

    public function test_generate_pie_chart_data_with_data()
    {
        SurveyResponse::factory()->create([
            'overall_satisfaction' => 5,
            'consent_given' => true,
            'track' => 'CSS',
        ]);

        $result = $this->service->generatePieChartData('CSS');

        $this->assertArrayHasKey('labels', $result);
        $this->assertArrayHasKey('datasets', $result);
        $this->assertIsArray($result['datasets'][0]['data']);
    }

    public function test_generate_line_chart_data_with_empty_data()
    {
        $result = $this->service->generateLineChartData('NONEXISTENT_TRACK');

        $this->assertArrayHasKey('labels', $result);
        $this->assertArrayHasKey('datasets', $result);
    }

    public function test_generate_line_chart_data_with_data()
    {
        SurveyResponse::factory()->count(3)->create([
            'overall_satisfaction' => 4,
            'consent_given' => true,
            'track' => 'CSS',
            'created_at' => now()->subDays(7),
        ]);

        $result = $this->service->generateLineChartData('CSS');

        $this->assertArrayHasKey('labels', $result);
        $this->assertArrayHasKey('datasets', $result);
    }
}



