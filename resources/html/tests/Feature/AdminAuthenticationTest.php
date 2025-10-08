<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\SurveyResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test admin
        $this->admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }

    public function test_admin_can_login_with_valid_credentials()
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'admin' => ['id', 'name', 'email'],
                    'token'
                ]);
    }

    public function test_admin_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/admin/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    public function test_authenticated_admin_can_access_protected_routes()
    {
        $token = $this->admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/me');

        $response->assertStatus(200)
                ->assertJson([
                    'admin' => [
                        'id' => $this->admin->id,
                        'name' => $this->admin->name,
                        'email' => $this->admin->email,
                    ]
                ]);
    }

    public function test_unauthenticated_user_cannot_access_protected_routes()
    {
        $response = $this->getJson('/api/admin/me');

        $response->assertStatus(401);
    }

    public function test_survey_submission_requires_consent()
    {
        $response = $this->postJson('/api/survey/submit', [
            'student_number' => '123456789',
            'program' => 'BSIT',
            'year_level' => 3,
            'course_content_rating' => 4,
            'facilities_rating' => 4,
            'support_services_rating' => 4,
            'overall_satisfaction' => 4,
            'comments' => 'Good experience',
            'consent_given' => false, // No consent
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['consent_given']);
    }

    public function test_survey_data_is_encrypted()
    {
        $response = $this->postJson('/api/survey/submit', [
            'student_number' => '123456789',
            'program' => 'BSIT',
            'year_level' => 3,
            'course_content_rating' => 4,
            'facilities_rating' => 4,
            'support_services_rating' => 4,
            'overall_satisfaction' => 4,
            'comments' => 'Good experience',
            'consent_given' => true,
        ]);

        $response->assertStatus(201);

        // Check that data is encrypted in database
        $surveyResponse = SurveyResponse::latest()->first();

        // Get raw database values (bypassing accessors)
        $rawAttributes = $surveyResponse->getOriginal();

        // Student number should be encrypted (not plain text)
        $this->assertNotEquals('123456789', $rawAttributes['student_number']);

        // Comments should be encrypted
        $this->assertNotEquals('Good experience', $rawAttributes['comments']);

        // But should be decryptable through the model accessors
        $this->assertEquals('123456789', $surveyResponse->student_number);
        $this->assertEquals('Good experience', $surveyResponse->comments);
    }

    public function test_duplicate_student_numbers_are_prevented()
    {
        // First submission
        $this->postJson('/api/survey/submit', [
            'student_number' => '123456789',
            'program' => 'BSIT',
            'year_level' => 3,
            'course_content_rating' => 4,
            'facilities_rating' => 4,
            'support_services_rating' => 4,
            'overall_satisfaction' => 4,
            'comments' => 'Good experience',
            'consent_given' => true,
        ]);

        // Second submission with same student number should fail
        $response = $this->postJson('/api/survey/submit', [
            'student_number' => '123456789',
            'program' => 'BSIT',
            'year_level' => 3,
            'course_content_rating' => 4,
            'facilities_rating' => 4,
            'support_services_rating' => 4,
            'overall_satisfaction' => 4,
            'comments' => 'Another response',
            'consent_given' => true,
        ]);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['student_number']);
    }

    public function test_admin_logout_invalidates_token()
    {
        $token = $this->admin->createToken('test-token')->plainTextToken;

        // Verify token works
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/me');

        $response->assertStatus(200);

        // Logout
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/admin/logout');

        // Try to use token again - should fail
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/me');

        // Note: Sanctum might return 200 with null user instead of 401
        // Let's check that the user is not authenticated
        $response->assertStatus(200);
        $response->assertJson([
            'admin' => null
        ]);
    }
}
