<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Services\EncryptionService;
use App\Services\AnonymizationService;

/**
 * SurveyResponse Model
 *
 * Handles ISO 21001 Quality Education survey responses for CSS (Computer System Servicing) students.
 * All data follows ISO 21001 standards for learner-centric quality education assessment.
 *
 * @property string $track Should be 'CSS' for Computer System Servicing students
 */
class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'track',
        'grade_level',
        'academic_year',
        'semester',
        'gender',
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

    /**
     * Get encryption service instance
     *
     * @return EncryptionService
     */
    protected function encryptionService(): EncryptionService
    {
        return app(EncryptionService::class);
    }

    /**
     * Get anonymization service instance
     *
     * @return AnonymizationService
     */
    protected function anonymizationService(): AnonymizationService
    {
        return app(AnonymizationService::class);
    }

    // Mutators for encryption using EncryptionService (AES-256)
    public function setStudentIdAttribute($value)
    {
        $this->attributes['student_id'] = !empty($value)
            ? $this->encryptionService()->encrypt($value)
            : $value;
    }

    public function setPositiveAspectsAttribute($value)
    {
        $this->attributes['positive_aspects'] = !empty($value)
            ? $this->encryptionService()->encrypt($value)
            : $value;
    }

    public function setImprovementSuggestionsAttribute($value)
    {
        $this->attributes['improvement_suggestions'] = !empty($value)
            ? $this->encryptionService()->encrypt($value)
            : $value;
    }

    public function setAdditionalCommentsAttribute($value)
    {
        $this->attributes['additional_comments'] = !empty($value)
            ? $this->encryptionService()->encrypt($value)
            : $value;
    }

    /**
     * Cache decrypted values to improve performance.
     * Cache key format: survey_response_{id}_{field}
     * Cache duration: 1 hour (3600 seconds)
     */
    private function getCachedDecryptedValue(string $field, $encryptedValue, int $cacheDuration = 3600)
    {
        if (empty($encryptedValue)) {
            return null;
        }

        // Generate cache key based on model ID and field name
        $cacheKey = "survey_response_{$this->id}_{$field}";

        // Try to get from cache first
        return Cache::remember($cacheKey, $cacheDuration, function () use ($encryptedValue, $field) {
            try {
                return $this->encryptionService()->decrypt($encryptedValue);
            } catch (\Exception $e) {
                Log::error("Failed to decrypt {$field}: " . $e->getMessage(), [
                    'response_id' => $this->id,
                    'field' => $field,
                ]);
                return null;
            }
        });
    }

    /**
     * Clear cached decrypted values for this model instance.
     * Should be called when the model is updated.
     */
    private function clearDecryptionCache()
    {
        $fields = ['student_id', 'positive_aspects', 'improvement_suggestions', 'additional_comments'];
        foreach ($fields as $field) {
            Cache::forget("survey_response_{$this->id}_{$field}");
        }
    }

    /**
     * Override the save method to clear cache when model is updated.
     */
    public function save(array $options = [])
    {
        $wasRecentlyCreated = $this->wasRecentlyCreated;
        $result = parent::save($options);

        // Clear cache after save (but not on first creation)
        if (!$wasRecentlyCreated) {
            $this->clearDecryptionCache();
        }

        return $result;
    }

    // Accessors for decryption with caching
    public function getStudentIdAttribute($value)
    {
        // Get raw encrypted value from attributes
        $rawValue = $this->attributes['student_id'] ?? $value;
        return $this->getCachedDecryptedValue('student_id', $rawValue);
    }

    public function getPositiveAspectsAttribute($value)
    {
        // Get raw encrypted value from attributes
        $rawValue = $this->attributes['positive_aspects'] ?? $value;
        return $this->getCachedDecryptedValue('positive_aspects', $rawValue);
    }

    public function getImprovementSuggestionsAttribute($value)
    {
        // Get raw encrypted value from attributes
        $rawValue = $this->attributes['improvement_suggestions'] ?? $value;
        return $this->getCachedDecryptedValue('improvement_suggestions', $rawValue);
    }

    public function getAdditionalCommentsAttribute($value)
    {
        // Get raw encrypted value from attributes
        $rawValue = $this->attributes['additional_comments'] ?? $value;
        return $this->getCachedDecryptedValue('additional_comments', $rawValue);
    }

    /**
     * Generate anonymous ID for analytics using SHA-256 (GDPR & ISO 27001 compliant)
     *
     * @return string SHA-256 anonymous ID
     */
    public function getAnonymousIdAttribute()
    {
        $studentId = $this->attributes['student_id'] ?? null;
        if (!$studentId) {
            return null;
        }

        // Decrypt student_id to generate consistent anonymous ID
        try {
            $decryptedStudentId = $this->encryptionService()->decrypt($studentId);
            if ($decryptedStudentId) {
                return $this->anonymizationService()->getResponseAnonymousId(
                    $decryptedStudentId,
                    $this->created_at?->toIso8601String()
                );
            }
        } catch (\Exception $e) {
            Log::warning('Failed to generate anonymous ID', [
                'response_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Fallback: use encrypted value directly (less ideal but still anonymous)
        return $this->anonymizationService()->anonymizeForAnalytics(
            $studentId,
            ['created_at' => $this->created_at?->toIso8601String()]
        );
    }
}


