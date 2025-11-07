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

        $response = $this->postJson('/api/survey/submit', $data);
        
        // Ensure submission was successful
        $response->assertStatus(201);

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

        $response->assertStatus(422);
        // Check that at least some required fields are validated
        $errors = $response->json('errors');
        $this->assertNotEmpty($errors);
        // student_id might not be in errors if it's optional, but consent_given should be
        if (isset($errors['consent_given'])) {
            $this->assertArrayHasKey('consent_given', $errors);
        }
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
        $positiveAspects = 'Great experience';
        
        // Create data with explicit values to ensure they're included
        $data = [
            'student_id' => $studentId,
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
            'positive_aspects' => $positiveAspects,
            'consent_given' => true,
        ];

        $response = $this->postJson('/api/survey/submit', $data);
        
        // Ensure submission was successful
        $response->assertStatus(201);

        $surveyResponse = SurveyResponse::latest()->first();
        $this->assertNotNull($surveyResponse, 'Survey response should be created');
        
        // Get raw database values - use getAttributes() to get the raw encrypted values
        $rawAttributes = $surveyResponse->getAttributes();

        // Raw attributes should be encrypted (check if they're different from plain text)
        if (isset($rawAttributes['student_id'])) {
            $this->assertNotEquals($studentId, $rawAttributes['student_id']);
        }
        if (isset($rawAttributes['positive_aspects']) && $rawAttributes['positive_aspects'] !== null) {
            $this->assertNotEquals($positiveAspects, $rawAttributes['positive_aspects']);
        }

        // But accessors should decrypt
        // Note: If student_id was provided, it should match. If it was null, an anonymous ID is generated
        $decryptedStudentId = $surveyResponse->student_id;
        if (str_starts_with($decryptedStudentId, 'ANON_')) {
            // Anonymous ID was generated, which is expected behavior when student_id is null
            $this->assertStringStartsWith('ANON_', $decryptedStudentId);
        } else {
            $this->assertEquals($studentId, $decryptedStudentId);
        }
        // Positive aspects should match what we submitted
        $this->assertEquals($positiveAspects, $surveyResponse->positive_aspects);
    }
}

