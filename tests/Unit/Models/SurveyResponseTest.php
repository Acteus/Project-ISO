<?php

namespace Tests\Unit\Models;

use App\Models\SurveyResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class SurveyResponseTest extends TestCase
{
    use RefreshDatabase;

    public function test_survey_response_factory_creates_response()
    {
        $response = SurveyResponse::factory()->create();

        $this->assertDatabaseHas('survey_responses', [
            'id' => $response->id,
            'track' => $response->track,
        ]);
    }

    public function test_sensitive_fields_are_encrypted()
    {
        $studentId = 'STU12345';
        $positiveAspects = 'Great experience';

        $response = SurveyResponse::factory()->create([
            'student_id' => $studentId,
            'positive_aspects' => $positiveAspects,
        ]);

        // Raw attributes should be encrypted
        $rawAttributes = $response->getAttributes();
        $this->assertNotEquals($studentId, $rawAttributes['student_id']);
        $this->assertNotEquals($positiveAspects, $rawAttributes['positive_aspects']);

        // But accessors should decrypt them
        $this->assertEquals($studentId, $response->student_id);
        $this->assertEquals($positiveAspects, $response->positive_aspects);
    }

    public function test_anonymous_id_is_generated()
    {
        $response = SurveyResponse::factory()->create();

        $anonymousId = $response->anonymous_id;

        $this->assertNotNull($anonymousId);
        $this->assertEquals(64, strlen($anonymousId)); // SHA256 hash length
    }

    public function test_ratings_are_casted_to_integers()
    {
        $response = SurveyResponse::factory()->create([
            'curriculum_relevance_rating' => '4',
            'overall_satisfaction' => '5',
        ]);

        $this->assertIsInt($response->curriculum_relevance_rating);
        $this->assertIsInt($response->overall_satisfaction);
    }

    public function test_decimal_fields_are_casted_correctly()
    {
        $response = SurveyResponse::factory()->create([
            'attendance_rate' => 95.75,
            'grade_average' => 3.85,
        ]);

        $this->assertEquals(95.75, $response->attendance_rate);
        $this->assertEquals(3.85, $response->grade_average);
    }

    public function test_consent_given_is_casted_to_boolean()
    {
        $response = SurveyResponse::factory()->create([
            'consent_given' => true,
        ]);

        $this->assertTrue($response->consent_given);
        $this->assertIsBool($response->consent_given);
    }

    public function test_sensitive_fields_are_hidden_in_array()
    {
        $response = SurveyResponse::factory()->create([
            'student_id' => 'STU12345',
            'positive_aspects' => 'Test',
        ]);

        $array = $response->toArray();

        $this->assertArrayNotHasKey('student_id', $array);
        $this->assertArrayNotHasKey('positive_aspects', $array);
        $this->assertArrayNotHasKey('ip_address', $array);
    }

    public function test_empty_sensitive_fields_return_null()
    {
        $response = SurveyResponse::factory()->create([
            'student_id' => '',
            'positive_aspects' => null,
        ]);

        $this->assertNull($response->student_id);
        $this->assertNull($response->positive_aspects);
    }
}


