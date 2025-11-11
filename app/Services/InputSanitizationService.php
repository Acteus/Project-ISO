<?php

namespace App\Services;

/**
 * Input Sanitization Service
 *
 * Provides comprehensive input sanitization to prevent XSS attacks
 * and ensure data integrity before storing in the database.
 */
class InputSanitizationService
{
    /**
     * Sanitize a string input by stripping HTML tags
     *
     * NOTE: For encrypted fields, we don't encode HTML entities here because:
     * - Encryption service needs plain text
     * - Laravel Blade automatically escapes output
     *
     * @param string|null $input
     * @param bool $allowBasicFormatting Allow basic formatting tags like <b>, <i>, <p>
     * @param bool $encodeEntities Whether to encode HTML entities (default: false for compatibility with encryption)
     * @return string
     */
    public function sanitizeString(?string $input, bool $allowBasicFormatting = false, bool $encodeEntities = false): string
    {
        if ($input === null) {
            return '';
        }

        // Remove null bytes and other control characters
        $input = str_replace(["\0", "\x08", "\x09", "\x1a", "\x1b"], '', $input);

        // Trim whitespace
        $input = trim($input);

        if ($allowBasicFormatting) {
            // Allow only safe HTML tags for formatting
            $allowedTags = '<b><i><u><p><br><strong><em><ul><ol><li>';
            $input = strip_tags($input, $allowedTags);

            // Clean up attributes that might contain javascript
            $input = preg_replace_callback('/<([^>]+)>/i', function($matches) {
                $tag = $matches[1];
                // Remove all attributes except safe ones
                $tag = preg_replace('/\s*(on\w+|javascript:|data:|style\s*=\s*["\'][^"\']*["\'])/i', '', $tag);
                return '<' . trim($tag) . '>';
            }, $input);
        } else {
            // Strip all HTML tags
            $input = strip_tags($input);
        }

        // Only encode HTML entities if explicitly requested
        // Default is false to maintain compatibility with encryption
        if ($encodeEntities) {
            $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
        }

        return $input;
    }

    /**
     * Sanitize an array of inputs
     *
     * @param array $inputs
     * @param bool $allowBasicFormatting
     * @return array
     */
    public function sanitizeArray(array $inputs, bool $allowBasicFormatting = false): array
    {
        $sanitized = [];

        foreach ($inputs as $key => $value) {
            if (is_string($value)) {
                $sanitized[$key] = $this->sanitizeString($value, $allowBasicFormatting);
            } elseif (is_array($value)) {
                $sanitized[$key] = $this->sanitizeArray($value, $allowBasicFormatting);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Sanitize text fields that are stored in the database
     * This is more restrictive and strips all HTML
     *
     * IMPORTANT: Does NOT encode HTML entities because:
     * 1. Encrypted fields need plain text for encryption/decryption
     * 2. Laravel Blade templates automatically escape output
     * 3. Encoding before encryption would break the encryption flow
     *
     * @param string|null $input
     * @param bool $encodeEntities Whether to encode HTML entities (default: false for encrypted fields)
     * @return string
     */
    public function sanitizeForDatabase(?string $input, bool $encodeEntities = false): string
    {
        if ($input === null) {
            return '';
        }

        // Remove null bytes and control characters
        $input = str_replace(["\0", "\x08", "\x09", "\x1a", "\x1b"], '', $input);

        // Trim whitespace
        $input = trim($input);

        // Strip all HTML tags (prevents XSS)
        $input = strip_tags($input);

        // Remove javascript: and data: protocols
        $input = preg_replace('/(javascript|data|vbscript):/i', '', $input);

        // Remove event handlers (onclick, onerror, etc.)
        $input = preg_replace('/\s*on\w+\s*=\s*["\'][^"\']*["\']/i', '', $input);

        // Normalize whitespace
        $input = preg_replace('/\s+/', ' ', $input);

        // Only encode HTML entities if explicitly requested
        // For encrypted fields, we don't encode because:
        // - Encryption service needs plain text
        // - Blade templates will escape on output
        if ($encodeEntities) {
            $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
        }

        return $input;
    }

    /**
     * Sanitize query parameters to prevent injection attacks
     *
     * @param string|null $input
     * @param string $type Type of parameter: 'string', 'integer', 'date', 'email'
     * @return mixed
     */
    public function sanitizeQueryParameter(?string $input, string $type = 'string')
    {
        if ($input === null || $input === '') {
            return null;
        }

        switch ($type) {
            case 'integer':
                return filter_var($input, FILTER_VALIDATE_INT, [
                    'options' => ['min_range' => 0, 'max_range' => 2147483647]
                ]) !== false ? (int) $input : null;

            case 'date':
                // Validate date format (YYYY-MM-DD)
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $input)) {
                    $date = \DateTime::createFromFormat('Y-m-d', $input);
                    return $date && $date->format('Y-m-d') === $input ? $input : null;
                }
                return null;

            case 'email':
                return filter_var($input, FILTER_VALIDATE_EMAIL) ?: null;

            case 'track':
                // Allow only specific track values
                $allowedTracks = ['CSS'];
                return in_array(strtoupper($input), $allowedTracks) ? strtoupper($input) : null;

            case 'semester':
                // Allow only specific semester values
                $allowedSemesters = ['1st', '2nd'];
                return in_array($input, $allowedSemesters) ? $input : null;

            case 'grade_level':
                // Allow only specific grade levels
                $allowedGrades = ['11', '12'];
                return in_array($input, $allowedGrades) ? (int) $input : null;

            case 'gender':
                // Allow only specific gender values
                $allowedGenders = ['Male', 'Female', 'Non-binary', 'Prefer not to say'];
                return in_array($input, $allowedGenders) ? $input : null;

            case 'academic_year':
                // Validate academic year format (YYYY-YYYY or YYYY)
                if (preg_match('/^\d{4}-\d{4}$/', $input) || preg_match('/^\d{4}$/', $input)) {
                    return $this->sanitizeString($input);
                }
                return null;

            case 'string':
            default:
                // Sanitize string input
                $input = $this->sanitizeString($input);
                // Limit length to prevent DoS
                return mb_substr($input, 0, 255);
        }
    }

    /**
     * Sanitize request data for survey submission
     *
     * @param array $data
     * @return array
     */
    public function sanitizeSurveyData(array $data): array
    {
        $sanitized = [];

        // Sanitize text fields
        $textFields = [
            'student_id',
            'positive_aspects',
            'improvement_suggestions',
            'additional_comments',
            'track',
            'academic_year',
            'semester',
            'gender'
        ];

        foreach ($textFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $sanitized[$field] = $this->sanitizeForDatabase($data[$field]);
            } elseif (isset($data[$field])) {
                $sanitized[$field] = $data[$field];
            }
        }

        // Keep numeric fields as-is (they're validated separately)
        $numericFields = [
            'grade_level',
            'curriculum_relevance_rating',
            'learning_pace_appropriateness',
            'individual_support_availability',
            'learning_style_accommodation',
            'teaching_quality_rating',
            'learning_environment_rating',
            'peer_interaction_satisfaction',
            'extracurricular_satisfaction',
            'academic_progress_rating',
            'skill_development_rating',
            'critical_thinking_improvement',
            'problem_solving_confidence',
            'physical_safety_rating',
            'psychological_safety_rating',
            'bullying_prevention_effectiveness',
            'emergency_preparedness_rating',
            'mental_health_support_rating',
            'stress_management_support',
            'physical_health_support',
            'overall_wellbeing_rating',
            'overall_satisfaction',
            'feedback_taken_seriously',
            'school_responsiveness',
            'visible_improvements',
            'attendance_rate',
            'grade_average',
            'participation_score',
            'extracurricular_hours',
            'counseling_sessions',
        ];

        foreach ($numericFields as $field) {
            if (isset($data[$field])) {
                $sanitized[$field] = $data[$field];
            }
        }

        // Keep boolean fields as-is
        if (isset($data['consent_given'])) {
            $sanitized['consent_given'] = (bool) $data['consent_given'];
        }

        // Merge any other fields that weren't explicitly handled
        foreach ($data as $key => $value) {
            if (!isset($sanitized[$key])) {
                $sanitized[$key] = is_string($value) ? $this->sanitizeForDatabase($value) : $value;
            }
        }

        return $sanitized;
    }
}

