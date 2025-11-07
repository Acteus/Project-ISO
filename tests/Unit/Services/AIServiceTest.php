<?php

namespace Tests\Unit\Services;

use App\Services\AIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AIServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AIService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AIService();
    }

    public function test_predict_compliance_with_high_scores()
    {
        $data = [
            'learner_needs_index' => 4.5,
            'satisfaction_score' => 4.8,
            'success_index' => 4.6,
            'safety_index' => 4.9,
            'wellbeing_index' => 4.4,
            'overall_satisfaction' => 5.0,
        ];

        $result = $this->service->predictCompliance($data);

        $this->assertArrayHasKey('prediction', $result);
        $this->assertArrayHasKey('risk_level', $result);
        $this->assertArrayHasKey('confidence', $result);
        $this->assertArrayHasKey('weighted_score', $result);
        $this->assertStringContainsString('High', $result['prediction']);
        $this->assertEquals('Low', $result['risk_level']);
    }

    public function test_predict_compliance_with_low_scores()
    {
        $data = [
            'learner_needs_index' => 2.1,
            'satisfaction_score' => 2.3,
            'success_index' => 2.0,
            'safety_index' => 1.8,
            'wellbeing_index' => 2.2,
            'overall_satisfaction' => 2.0,
        ];

        $result = $this->service->predictCompliance($data);

        $this->assertStringContainsString('Low', $result['prediction']);
        $this->assertEquals('High', $result['risk_level']);
    }

    public function test_predict_compliance_with_moderate_scores()
    {
        $data = [
            'learner_needs_index' => 3.6,
            'satisfaction_score' => 3.7,
            'success_index' => 3.5,
            'safety_index' => 3.8,
            'wellbeing_index' => 3.6,
            'overall_satisfaction' => 3.5,
        ];

        $result = $this->service->predictCompliance($data);

        $this->assertStringContainsString('Moderate', $result['prediction']);
        $this->assertEquals('Medium', $result['risk_level']);
    }

    public function test_analyze_sentiment_with_positive_comments()
    {
        $comments = 'Great experience! The instructors are excellent and very supportive.';

        $result = $this->service->analyzeSentiment($comments);

        $this->assertArrayHasKey('sentiment', $result);
        $this->assertArrayHasKey('score', $result);
        $this->assertEquals('positive', $result['sentiment']);
    }

    public function test_analyze_sentiment_with_negative_comments()
    {
        $comments = 'Terrible experience. The facilities are poor and outdated.';

        $result = $this->service->analyzeSentiment($comments);

        $this->assertEquals('negative', $result['sentiment']);
    }

    public function test_analyze_sentiment_with_neutral_comments()
    {
        $comments = 'The program is okay. Nothing special.';

        $result = $this->service->analyzeSentiment($comments);

        $this->assertEquals('neutral', $result['sentiment']);
    }

    public function test_predict_compliance_handles_missing_fields()
    {
        $data = [
            'learner_needs_index' => 4.0,
            'satisfaction_score' => 4.0,
        ];

        $result = $this->service->predictCompliance($data);

        $this->assertArrayHasKey('prediction', $result);
        $this->assertArrayHasKey('weighted_score', $result);
    }

    public function test_predict_compliance_calculates_weighted_score()
    {
        $data = [
            'learner_needs_index' => 4.0,
            'satisfaction_score' => 4.0,
            'success_index' => 4.0,
            'safety_index' => 4.0,
            'wellbeing_index' => 4.0,
            'overall_satisfaction' => 4.0,
        ];

        $result = $this->service->predictCompliance($data);

        $this->assertIsFloat($result['weighted_score']);
        $this->assertGreaterThan(0, $result['weighted_score']);
        $this->assertLessThanOrEqual(5, $result['weighted_score']);
    }
}



