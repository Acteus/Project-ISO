<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'track',
        'grade_level',
        'academic_year',
        'semester',
        // ISO 21001 Learner Needs Assessment
        'curriculum_relevance_rating',
        'learning_pace_appropriateness',
        'individual_support_availability',
        'learning_style_accommodation',
        // ISO 21001 Learner Satisfaction Metrics
        'teaching_quality_rating',
        'learning_environment_rating',
        'peer_interaction_satisfaction',
        'extracurricular_satisfaction',
        // ISO 21001 Learner Success Indicators
        'academic_progress_rating',
        'skill_development_rating',
        'critical_thinking_improvement',
        'problem_solving_confidence',
        // ISO 21001 Learner Safety Assessment
        'physical_safety_rating',
        'psychological_safety_rating',
        'bullying_prevention_effectiveness',
        'emergency_preparedness_rating',
        // ISO 21001 Learner Wellbeing Metrics
        'mental_health_support_rating',
        'stress_management_support',
        'physical_health_support',
        'overall_wellbeing_rating',
        // Overall Satisfaction and Feedback
        'overall_satisfaction',
        'positive_aspects',
        'improvement_suggestions',
        'additional_comments',
        // Privacy and Consent
        'consent_given',
        'ip_address',
        // Indirect Metrics from University Data
        'attendance_rate',
        'grade_average',
        'participation_score',
        'extracurricular_hours',
        'counseling_sessions',
    ];

    protected $casts = [
        // Student Information
        'grade_level' => 'integer',
        'consent_given' => 'boolean',
        // ISO 21001 Rating Fields (1-5 scale)
        'curriculum_relevance_rating' => 'integer',
        'learning_pace_appropriateness' => 'integer',
        'individual_support_availability' => 'integer',
        'learning_style_accommodation' => 'integer',
        'teaching_quality_rating' => 'integer',
        'learning_environment_rating' => 'integer',
        'peer_interaction_satisfaction' => 'integer',
        'extracurricular_satisfaction' => 'integer',
        'academic_progress_rating' => 'integer',
        'skill_development_rating' => 'integer',
        'critical_thinking_improvement' => 'integer',
        'problem_solving_confidence' => 'integer',
        'physical_safety_rating' => 'integer',
        'psychological_safety_rating' => 'integer',
        'bullying_prevention_effectiveness' => 'integer',
        'emergency_preparedness_rating' => 'integer',
        'mental_health_support_rating' => 'integer',
        'stress_management_support' => 'integer',
        'physical_health_support' => 'integer',
        'overall_wellbeing_rating' => 'integer',
        'overall_satisfaction' => 'integer',
        // Indirect Metrics
        'attendance_rate' => 'decimal:2',
        'grade_average' => 'decimal:2',
        'participation_score' => 'integer',
        'extracurricular_hours' => 'integer',
        'counseling_sessions' => 'integer',
    ];

    protected $hidden = [
        'student_id',
        'positive_aspects',
        'improvement_suggestions',
        'additional_comments',
        'ip_address',
    ];

    // Mutators for encryption
    public function setStudentIdAttribute($value)
    {
        $this->attributes['student_id'] = !empty($value) ? Crypt::encryptString($value) : $value;
    }

    public function setPositiveAspectsAttribute($value)
    {
        $this->attributes['positive_aspects'] = !empty($value) ? Crypt::encryptString($value) : $value;
    }

    public function setImprovementSuggestionsAttribute($value)
    {
        $this->attributes['improvement_suggestions'] = !empty($value) ? Crypt::encryptString($value) : $value;
    }

    public function setAdditionalCommentsAttribute($value)
    {
        $this->attributes['additional_comments'] = !empty($value) ? Crypt::encryptString($value) : $value;
    }

    // Accessors for decryption with logging
    public function getStudentIdAttribute($value)
    {
        try {
            return !empty($value) ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            Log::error('Failed to decrypt student_id: ' . $e->getMessage());
            return null;
        }
    }

    public function getPositiveAspectsAttribute($value)
    {
        if (empty($value)) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            Log::error('Failed to decrypt positive_aspects: ' . $e->getMessage());
            return null;
        }
    }

    public function getImprovementSuggestionsAttribute($value)
    {
        if (empty($value)) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            Log::error('Failed to decrypt improvement_suggestions: ' . $e->getMessage());
            return null;
        }
    }

    public function getAdditionalCommentsAttribute($value)
    {
        if (empty($value)) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            Log::error('Failed to decrypt additional_comments: ' . $e->getMessage());
            return null;
        }
    }

    // Generate anonymous ID for analytics
    public function getAnonymousIdAttribute()
    {
        return hash('sha256', $this->student_id . $this->created_at);
    }
}
