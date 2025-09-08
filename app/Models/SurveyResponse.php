<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

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

    // Encrypt sensitive data before saving
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->student_id) {
                $model->student_id = Crypt::encryptString($model->student_id);
            }
            if ($model->positive_aspects) {
                $model->positive_aspects = Crypt::encryptString($model->positive_aspects);
            }
            if ($model->improvement_suggestions) {
                $model->improvement_suggestions = Crypt::encryptString($model->improvement_suggestions);
            }
            if ($model->additional_comments) {
                $model->additional_comments = Crypt::encryptString($model->additional_comments);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('student_id')) {
                $model->student_id = Crypt::encryptString($model->student_id);
            }
            if ($model->isDirty('positive_aspects')) {
                $model->positive_aspects = Crypt::encryptString($model->positive_aspects);
            }
            if ($model->isDirty('improvement_suggestions')) {
                $model->improvement_suggestions = Crypt::encryptString($model->improvement_suggestions);
            }
            if ($model->isDirty('additional_comments')) {
                $model->additional_comments = Crypt::encryptString($model->additional_comments);
            }
        });
    }

    // Decrypt sensitive data when retrieving
    public function getStudentIdAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getPositiveAspectsAttribute($value)
    {
        if (!$value) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getImprovementSuggestionsAttribute($value)
    {
        if (!$value) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getAdditionalCommentsAttribute($value)
    {
        if (!$value) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Generate anonymous ID for analytics
    public function getAnonymousIdAttribute()
    {
        return hash('sha256', $this->student_id . $this->created_at);
    }
}
