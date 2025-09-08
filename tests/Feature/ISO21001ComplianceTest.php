<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\SurveyResponse;
use App\Models\AuditLog;
use App\Services\ValidationService;
use App\Services\AIService;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ISO21001ComplianceTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $validSurveyData;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user for authenticated tests
        $this->adminUser = User::factory()->create([
            'email' => 'admin@iso21001.test',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $this->validSurveyData = [
            'student_id' => 'STU' . rand(100000, 999999),
            'track' => 'STEM',
            'grade_level' => 11,
            'academic_year' => '2024-2025',
            'semester' => '1st',
            // ISO 21001 Learner Needs Assessment (1-5 scale)
            'curriculum_relevance_rating' => 4,
            'learning_pace_appropriateness' => 3,
            'individual_support_availability' => 5,
            'learning_style_accommodation' => 4,
            // ISO 21001 Learner Satisfaction Metrics (1-5 scale)
            'teaching_quality_rating' => 4,
            'learning_environment_rating' => 5,
            'peer_interaction_satisfaction' => 3,
            'extracurricular_satisfaction' => 4,
            // ISO 21001 Learner Success Indicators (1-5 scale)
            'academic_progress_rating' => 4,
            'skill_development_rating' => 5,
            'critical_thinking_improvement' => 4,
            'problem_solving_confidence' => 4,
            // ISO 21001 Learner Safety Assessment (1-5 scale)
            'physical_safety_rating' => 5,
            'psychological_safety_rating' => 4,
            'bullying_prevention_effectiveness' => 5,
            'emergency_preparedness_rating' => 4,
            // ISO 21001 Learner Wellbeing Metrics (1-5 scale)
            'mental_health_support_rating' => 3,
            'stress_management_support' => 3,
            'physical_health_support' => 4,
            'overall_wellbeing_rating' => 4,
            // Overall Satisfaction and Feedback
            'overall_satisfaction' => 4,
            'positive_aspects' => 'Great support from teachers and modern facilities',
            'improvement_suggestions' => 'More hands-on lab activities would be beneficial',
            'additional_comments' => 'Overall positive experience in STEM program',
            // Consent
            'consent_given' => true,
            // Indirect metrics
            'attendance_rate' => 95.5,
            'grade_average' => 3.8,
            'participation_score' => 88,
            'extracurricular_hours' => 12,
            'counseling_sessions' => 2,
        ];
    }

    /** @test */
    public function can_submit_valid_iso_21001_survey_response()
    {
        $response = $this->postJson('/api/survey/submit', $this->validSurveyData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'track',
                        'grade_level',
                        'academic_year',
                        'semester',
                        'curriculum_relevance_rating',
                        // ... other fields except sensitive ones
                    ]
                ])
                ->assertJson(['message' => 'Survey response submitted successfully']);

        // Verify record was created
        $this->assertDatabaseHas('survey_responses', [
            'track' => 'STEM',
            'grade_level' => 11,
            'overall_satisfaction' => 4,
            'consent_given' => true
        ]);

        // Verify sensitive data is encrypted
        $survey = SurveyResponse::latest()->first();
        $this->assertNotEquals($this->validSurveyData['student_id'], $survey->getAttributes()['student_id']);
        $this->assertNotEquals($this->validSurveyData['positive_aspects'], $survey->getAttributes()['positive_aspects']);

        // Verify decrypted accessors work
        $this->assertEquals($this->validSurveyData['student_id'], $survey->student_id);
        $this->assertEquals($this->validSurveyData['positive_aspects'], $survey->positive_aspects);
        $this->assertEquals($this->validSurveyData['positive_aspects'], $survey->positive_aspects);
    }

    /** @test */
    public function survey_submission_requires_consent_for_iso_21001_compliance()
    {
        $invalidData = $this->validSurveyData;
        $invalidData['consent_given'] = false;

        $response = $this->postJson('/api/survey/submit', $invalidData);

        $response->assertStatus(422)
                ->assertJsonStructure(['message', 'errors'])
                ->assertJsonValidationErrors(['consent_given']);

        $this->assertDatabaseMissing('survey_responses', [
            'student_id' => $invalidData['student_id'],
            'track' => 'STEM'
        ]);
    }

    /** @test */
    public function survey_submission_validates_iso_21001_rating_scales()
    {
        $invalidData = $this->validSurveyData;
        $invalidData['curriculum_relevance_rating'] = 6; // Invalid rating > 5

        $response = $this->postJson('/api/survey/submit', $invalidData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['curriculum_relevance_rating']);

        // Test minimum rating
        $invalidData['curriculum_relevance_rating'] = 0;
        $response = $this->postJson('/api/survey/submit', $invalidData);
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['curriculum_relevance_rating']);
    }

    /** @test */
    public function survey_submission_creates_audit_log_for_traceability()
    {
        // Test anonymous submission audit logging
        $response = $this->postJson('/api/survey/submit', $this->validSurveyData);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'submit_survey_response',
            'description' => 'Submitted ISO 21001 survey response (anonymous)'
        ]);

        // Test authenticated submission audit logging
        $this->actingAs($this->adminUser, 'sanctum');

        // Modify data to avoid unique constraint
        $this->validSurveyData['student_id'] .= '_admin';
        $response = $this->postJson('/api/survey/submit', $this->validSurveyData);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'submit_survey_response',
            'description' => 'Submitted ISO 21001 survey response (authenticated user)',
            'user_id' => $this->adminUser->id
        ]);
    }

    /** @test */
    public function analytics_endpoint_calculates_iso_21001_composite_indices_correctly()
    {
        // Create multiple survey responses for testing
        $surveys = SurveyResponse::factory()->count(5)->create([
            'track' => 'STEM',
            'grade_level' => 11,
            'academic_year' => '2024-2025',
            'semester' => '1st',
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
            'psychological_safety_rating' => 5,
            'bullying_prevention_effectiveness' => 5,
            'emergency_preparedness_rating' => 5,
            'mental_health_support_rating' => 4,
            'stress_management_support' => 4,
            'physical_health_support' => 4,
            'overall_wellbeing_rating' => 4,
            'overall_satisfaction' => 4,
            'consent_given' => true,
            'grade_average' => 3.8,
            'attendance_rate' => 95
        ]);

        $this->actingAs($this->adminUser, 'sanctum');
        $response = $this->getJson('/api/survey/analytics?track=STEM');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'total_responses',
                        'iso_21001_indices',
                        'indirect_metrics',
                        'correlation_analysis',
                        'distribution',
                        'consent_rate'
                    ]
                ]);

        $data = $response->json('data');

        // Verify ISO 21001 composite indices calculation accuracy
        $this->assertEquals(5, $data['total_responses']);

        // Learner Needs Index should be average of 4 ratings all at 4 = 4.0
        $this->assertEquals(4.0, $data['iso_21001_indices']['learner_needs_index']);

        // Satisfaction Score should be (5+5+4+4)/4 = 4.5
        $this->assertEquals(4.5, $data['iso_21001_indices']['satisfaction_score']);

        // Success Index should be 4.0
        $this->assertEquals(4.0, $data['iso_21001_indices']['success_index']);

        // Safety Index should be 5.0
        $this->assertEquals(5.0, $data['iso_21001_indices']['safety_index']);

        // Wellbeing Index should be 4.0
        $this->assertEquals(4.0, $data['iso_21001_indices']['wellbeing_index']);

        // Overall satisfaction should be 4.0
        $this->assertEquals(4.0, $data['iso_21001_indices']['overall_satisfaction']);

        // Consent rate should be 100%
        $this->assertEquals(100.0, $data['consent_rate']);

        // Verify correlation calculations
        $this->assertEquals(15.2, $data['correlation_analysis']['satisfaction_vs_performance_correlation']); // 4.0 * 3.8
    }

    /** @test */
    public function validation_service_detects_direct_vs_indirect_discrepancies()
    {
        // Create responses with high satisfaction but low performance (discrepancy)
        SurveyResponse::factory()->count(3)->create([
            'overall_satisfaction' => 5, // High satisfaction
            'grade_average' => 2.0,     // Low performance
            'attendance_rate' => 60,    // Low attendance
            'consent_given' => true,
            'track' => 'STEM'
        ]);

        // Create normal responses
        SurveyResponse::factory()->count(2)->create([
            'overall_satisfaction' => 4,
            'grade_average' => 3.5,
            'attendance_rate' => 90,
            'consent_given' => true,
            'track' => 'STEM'
        ]);

        $validationService = new ValidationService();
        $result = $validationService->validateDirectVsIndirect('STEM');

        $this->assertEquals('Validation analysis completed', $result['message']);
        $this->assertEquals(5, $result['total_responses']);

        // Should detect high satisfaction/low performance discrepancy
        $discrepancies = array_filter($result['discrepancies'], function($d) {
            return $d['type'] === 'HIGH_SATISFACTION_LOW_PERFORMANCE';
        });

        $this->assertNotEmpty($discrepancies);
        $this->assertEquals(3, $discrepancies[0]['count']);
        $this->assertEquals('HIGH', $discrepancies[0]['severity']);
        $this->assertLessThan(100, $result['validation_score']);
    }

    /** @test */
    public function validation_service_validates_accessibility_compliance()
    {
        // Create responses with accessibility issues
        SurveyResponse::factory()->count(2)->create([
            'learning_style_accommodation' => 1, // Poor accommodation
            'individual_support_availability' => 2, // Low support
            'learning_pace_appropriateness' => 1, // Inappropriate pace
            'consent_given' => true,
            'track' => 'STEM'
        ]);

        // Create compliant responses
        SurveyResponse::factory()->count(3)->create([
            'learning_style_accommodation' => 5,
            'individual_support_availability' => 5,
            'learning_pace_appropriateness' => 5,
            'consent_given' => true,
            'track' => 'STEM'
        ]);

        $validationService = new ValidationService();
        $result = $validationService->validateAccessibilityCompliance('STEM');

        $this->assertEquals(5, $result['total_responses']);

        // Should detect accessibility issues
        $this->assertGreaterThan(0, count($result['issues']));

        // Check specific issue types
        $accommodationIssues = array_filter($result['issues'], function($issue) {
            return $issue['type'] === 'LEARNING_STYLE_ACCOMMODATION';
        });

        $this->assertNotEmpty($accommodationIssues);
        $this->assertEquals(2, $accommodationIssues[0]['count']);
        $this->assertEquals('HIGH', $accommodationIssues[0]['severity']);

        // Accessibility score should be less than 100
        $this->assertLessThan(100, $result['accessibility_score']);
        $this->assertEquals('Non-Compliant', $result['compliance_status']);
    }

    /** @test */
    public function validation_service_validates_data_quality_and_detects_outliers()
    {
        // Create responses with data quality issues
        SurveyResponse::factory()->create([
            'consent_given' => false, // Missing consent
            'track' => 'STEM'
        ]);

        // Create response with missing metrics
        SurveyResponse::factory()->create([
            'curriculum_relevance_rating' => 1, // Valid but low for testing
            'consent_given' => true,
            'track' => 'STEM'
        ]);

        // Create responses for outlier detection
        SurveyResponse::factory()->count(5)->create([
            'overall_satisfaction' => 4, // Normal ratings
            'grade_average' => 3.5,
            'consent_given' => true,
            'track' => 'STEM'
        ]);

        $validationService = new ValidationService();
        $result = $validationService->validateDataQuality('STEM');

        // Should detect missing consent
        $consentIssues = array_filter($result['issues'], function($issue) {
            return $issue['type'] === 'MISSING_CONSENT';
        });
        $this->assertNotEmpty($consentIssues);

        // Create response with incomplete metrics for testing
        SurveyResponse::factory()->create([
            'consent_given' => true,
            'track' => 'STEM',
            'curriculum_relevance_rating' => '', // Empty required field
            'overall_satisfaction' => 4,
        ]);

        // Create outlier response after incomplete metrics
        SurveyResponse::factory()->create([
            'overall_satisfaction' => 1, // Extreme outlier
            'grade_average' => 1.0,
            'consent_given' => true,
            'track' => 'STEM'
        ]);

        // Should detect incomplete metrics
        $result = $validationService->validateDataQuality('STEM');
        $metricIssues = array_filter($result['issues'], function($issue) {
            return $issue['type'] === 'INCOMPLETE_METRICS';
        });
        $this->assertNotEmpty($metricIssues);

        // Should detect outliers
        $outlierIssues = array_filter($result['issues'], function($issue) {
            return $issue['type'] === 'DATA_OUTLIERS';
        });
        $this->assertNotEmpty($outlierIssues);

        // Data quality score should be less than 100
        $this->assertLessThan(100, $result['data_quality_score']);
        $this->assertEquals('Poor', $result['compliance_status']);

        $this->assertEquals(9, $result['total_responses']);
    }

    /** @test */
    public function ai_service_predicts_compliance_accurately()
    {
        $aiService = new AIService();

        // Test high compliance scenario
        $highComplianceData = [
            'learner_needs_index' => 4.5,
            'satisfaction_score' => 4.8,
            'success_index' => 4.6,
            'safety_index' => 4.9,
            'wellbeing_index' => 4.4,
            'overall_satisfaction' => 5
        ];

        $highResult = $aiService->predictCompliance($highComplianceData);

        $this->assertEquals('High ISO 21001 Compliance', $highResult['prediction']);
        $this->assertEquals('Low', $highResult['risk_level']);
        $this->assertGreaterThan(0.9, $highResult['confidence']);

        // Test low compliance scenario
        $lowComplianceData = [
            'learner_needs_index' => 2.1,
            'satisfaction_score' => 2.3,
            'success_index' => 2.0,
            'safety_index' => 1.8,
            'wellbeing_index' => 2.2,
            'overall_satisfaction' => 2
        ];

        $lowResult = $aiService->predictCompliance($lowComplianceData);

        $this->assertEquals('Low ISO 21001 Compliance', $lowResult['prediction']);
        $this->assertEquals('High', $lowResult['risk_level']);
        $this->assertLessThan(0.6, $lowResult['confidence']);

        // Verify weighted score calculation accuracy for high compliance data
        $expectedWeightedScore = (
            4.5 * 0.15 + 4.8 * 0.25 + 4.6 * 0.20 + 4.9 * 0.20 +
            4.4 * 0.15 + 5 * 0.05
        );
        $this->assertEqualsWithDelta($expectedWeightedScore, $highResult['weighted_score'], 0.1);
    }

    /** @test */
    public function export_controller_respects_privacy_and_logs_activity()
    {
        // Create test survey responses
        SurveyResponse::factory()->count(3)->create([
            'consent_given' => true,
            'track' => 'STEM',
            'overall_satisfaction' => 4
        ]);

        $this->actingAs($this->adminUser, 'sanctum');

        $response = $this->get('/api/export/excel?track=STEM');

        $response->assertStatus(200);

        // Verify audit log was created
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'export_excel',
            'user_id' => $this->adminUser->id,
            'description' => 'Exported ISO 21001 survey responses to Excel (Track: STEM, Grade: All, Year: All, Semester: All)'
        ]);
    }

    /** @test */
    public function visualization_service_handles_empty_data_gracefully()
    {
        $visualizationService = new \App\Services\VisualizationService();

        // Test with no data (empty filters that return no results)
        $barData = $visualizationService->generateBarChartData(
            'NONEXISTENT_TRACK', 99, '9999-9999', '99th'
        );

        $this->assertArrayHasKey('labels', $barData);
        $this->assertArrayHasKey('datasets', $barData);
        $this->assertEquals(6, count($barData['datasets'][0]['data'])); // Should return zeros for empty data

        // Test pie chart with empty data
        $pieData = $visualizationService->generatePieChartData(
            'NONEXISTENT_TRACK', 99, '9999-9999', '99th'
        );

        $this->assertEquals(5, count($pieData['datasets'][0]['data'])); // All zeros for empty data
    }

    /** @test */
    public function comprehensive_compliance_report_generates_correctly()
    {
        // Create mixed quality data for comprehensive testing
        SurveyResponse::factory()->count(2)->create([
            'overall_satisfaction' => 5,
            'grade_average' => 2.0, // High satisfaction, low performance
            'learning_style_accommodation' => 1, // Accessibility issue
            'consent_given' => false, // Consent issue
            'track' => 'STEM'
        ]);

        SurveyResponse::factory()->count(3)->create([
            'overall_satisfaction' => 4,
            'grade_average' => 3.8,
            'learning_style_accommodation' => 5,
            'consent_given' => true,
            'track' => 'STEM'
        ]);

        $validationService = new ValidationService();
        $report = $validationService->generateComprehensiveComplianceReport('STEM');

        $this->assertTrue($report['comprehensive_report']);
        $this->assertArrayHasKey('overall_compliance', $report);
        $this->assertLessThan(85, $report['overall_compliance']['score']); // Should be partially compliant
        $this->assertTrue($report['overall_compliance']['action_required']);
        $this->assertNotEmpty($report['recommendations']);
        $this->assertNotEmpty($report['overall_compliance']['priority_areas']);
    }

    /** @test */
    public function system_maintains_data_privacy_in_responses()
    {
        // Submit survey with sensitive data
        $response = $this->postJson('/api/survey/submit', $this->validSurveyData);
        $surveyId = $response->json('data.id');

        // Retrieve response - sensitive data should be hidden
        $this->actingAs($this->adminUser, 'sanctum');
        $getResponse = $this->getJson("/api/survey/responses/{$surveyId}");

        $getResponse->assertStatus(200)
                   ->assertJsonMissing(['student_id', 'positive_aspects', 'improvement_suggestions', 'additional_comments', 'ip_address'])
                   ->assertJsonStructure(['data' => ['anonymous_id']]); // Should include anonymous ID as string hash

        // Verify sensitive data is still accessible through model but hidden in JSON
        $survey = SurveyResponse::find($surveyId);
        $this->assertNotNull($survey->student_id); // Decrypted access works
        $this->assertNotNull($survey->positive_aspects); // Decrypted access works

        // But JSON response hides them
        $getResponse->assertJsonMissing(['student_id', 'positive_aspects']);
    }

    /** @test */
    public function audit_logs_maintain_complete_traceability()
    {
        $this->actingAs($this->adminUser, 'sanctum');

        // Test survey submission audit
        $this->postJson('/api/survey/submit', $this->validSurveyData);

        // Test analytics access audit
        $this->getJson('/api/survey/analytics?track=STEM');

        // Test export audit
        $this->get('/api/export/excel?track=STEM');

        // Test AI analysis audit
        $this->getJson('/api/ai/compliance-risk-meter?track=STEM');

        // Test visualization access audit
        $this->getJson('/api/visualization/dashboard?track=STEM');

        // Verify all audit events were logged
        $auditLogs = AuditLog::where('user_id', $this->adminUser->id)->get();

        $this->assertDatabaseHas('audit_logs', ['action' => 'submit_survey_response', 'user_id' => $this->adminUser->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'view_analytics', 'user_id' => $this->adminUser->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'export_excel', 'user_id' => $this->adminUser->id]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'view_analytics', 'user_id' => $this->adminUser->id]); // From AI endpoint
        $this->assertDatabaseHas('audit_logs', ['action' => 'view_analytics', 'user_id' => $this->adminUser->id]); // From visualization
    }

    /** @test */
    public function system_handles_edge_cases_and_error_conditions()
    {
        // Test empty analytics request
        $this->actingAs($this->adminUser, 'sanctum');
        $response = $this->getJson('/api/survey/analytics');
        $response->assertStatus(200)
                ->assertJson(['data' => []]);

        // Test non-existent survey retrieval
        $response = $this->getJson('/api/survey/responses/999999');
        $response->assertStatus(404);

        // Test invalid export parameters
        $this->actingAs($this->adminUser, 'sanctum');
        $response = $this->get('/api/export/excel?grade_level=invalid');
        $response->assertStatus(200); // Should still work with partial filtering

        // Test AI service with insufficient data
        $aiService = new AIService();
        $insufficientData = [
            'learner_needs_index' => 0,
            'satisfaction_score' => 0,
            'success_index' => 0,
            'safety_index' => 0,
            'wellbeing_index' => 0,
            'overall_satisfaction' => 0
        ];

        $result = $aiService->predictCompliance($insufficientData);
        $this->assertEquals('Low ISO 21001 Compliance', $result['prediction']);
    }
}

