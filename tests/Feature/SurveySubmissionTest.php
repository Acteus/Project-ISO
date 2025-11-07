<?php

namespace Tests\Feature;

use App\Models\SurveyResponse;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SurveySubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_survey_can_be_submitted_successfully()
    {
        $data = [
            'student_id' => 'STU12345',
            'track' => 'CSS',
            'grade_level' => 11,
            'academic_year' => '2024-2025',
            'semester' => '1st',
            'gender' => 'Male',
            'curriculum_relevance_rating' => 4,
            'learning_pace_appropriateness' => 4,
            'individual_support_availability' => 4,
            'learning_style_accommodation' => 4,
            'teaching_quality_rating' => 5,
            'learning_environment_rating' => 5,
            'peer_interaction_satisfaction' => 4,
            'extracurricular_satisfaction' => 4,
            'academic_progress_rating' => 4,
            'skill_development_rating' => 4,
            'critical_thinking_improvement' => 4,
            'problem_solving_confidence' => 4,
            'physical_safety_rating' => 5,
            'psychological_safety_rating' => 4,
            'bullying_prevention_effectiveness' => 5,
            'emergency_preparedness_rating' => 4,
            'mental_health_support_rating' => 4,
            'stress_management_support' => 4,
            'physical_health_support' => 4,
            'overall_wellbeing_rating' => 4,
            'overall_satisfaction' => 4,
            'consent_given' => true,
        ];

        $response = $this->postJson('/api/survey/submit', $data);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => ['id', 'track', 'grade_level']
                ]);

        $this->assertDatabaseHas('survey_responses', [
            'track' => 'CSS',
            'grade_level' => 11,
            'consent_given' => true,
        ]);
    }

    public function test_survey_submission_creates_audit_log()
    {
        $data = SurveyResponse::factory()->make()->toArray();
        $data['consent_given'] = true;

        $this->postJson('/api/survey/submit', $data);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'submit_survey_response',
        ]);
    }

    public function test_survey_submission_validates_required_fields()
    {
        $response = $this->postJson('/api/survey/submit', [
            'track' => 'CSS',
            // Missing required fields
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['student_id', 'consent_given']);
    }

    public function test_survey_submission_validates_rating_ranges()
    {
        $data = SurveyResponse::factory()->make()->toArray();
        $data['curriculum_relevance_rating'] = 6; // Invalid: should be 1-5
        $data['consent_given'] = true;

        $response = $this->postJson('/api/survey/submit', $data);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['curriculum_relevance_rating']);
    }

    public function test_survey_submission_validates_consent()
    {
        $data = SurveyResponse::factory()->make()->toArray();
        $data['consent_given'] = false;

        $response = $this->postJson('/api/survey/submit', $data);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['consent_given']);
    }

    public function test_survey_data_is_encrypted_in_database()
    {
        $studentId = 'STU12345';
        $data = SurveyResponse::factory()->make([
            'student_id' => $studentId,
            'positive_aspects' => 'Great experience',
        ])->toArray();
        $data['consent_given'] = true;

        $this->postJson('/api/survey/submit', $data);

        $response = SurveyResponse::latest()->first();
        $rawAttributes = $response->getAttributes();

        // Raw attributes should be encrypted
        $this->assertNotEquals($studentId, $rawAttributes['student_id']);
        $this->assertNotEquals('Great experience', $rawAttributes['positive_aspects']);

        // But accessors should decrypt
        $this->assertEquals($studentId, $response->student_id);
        $this->assertEquals('Great experience', $response->positive_aspects);
    }
}

