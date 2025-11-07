<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

/**
 * Anonymization Service
 * 
 * Provides SHA-256 anonymous IDs for analytics and reporting (GDPR & ISO 27001 compliant)
 * 
 * @package App\Services
 */
class AnonymizationService
{
    /**
     * Generate a SHA-256 anonymous ID for a student
     * 
     * @param string $studentId The original student ID
     * @param string|null $additionalData Additional data to include in hash (e.g., created_at)
     * @return string SHA-256 hash (64 character hexadecimal string)
     */
    public function generateAnonymousId(string $studentId, ?string $additionalData = null): string
    {
        $salt = Config::get('privacy.anonymization.salt', config('app.key'));
        
        // Combine student_id with salt and optional additional data
        $dataToHash = $studentId . $salt;
        if ($additionalData !== null) {
            $dataToHash .= $additionalData;
        }
        
        // Generate SHA-256 hash
        $anonymousId = hash('sha256', $dataToHash);
        
        return $anonymousId;
    }

    /**
     * Generate anonymous ID for analytics (one-way, cannot be reversed)
     * 
     * @param mixed $identifier Original identifier (student_id, response_id, etc.)
     * @param array $context Additional context data (optional)
     * @return string Anonymous ID
     */
    public function anonymizeForAnalytics($identifier, array $context = []): string
    {
        $salt = Config::get('privacy.anonymization.salt', config('app.key'));
        
        // Combine identifier with context and salt
        $dataToHash = (string) $identifier . $salt;
        if (!empty($context)) {
            $dataToHash .= json_encode($context);
        }
        
        return hash('sha256', $dataToHash);
    }

    /**
     * Generate consistent anonymous ID for a survey response
     * Uses student_id and created_at for consistency
     * 
     * @param string $studentId The student ID
     * @param string|null $createdAt Timestamp (optional)
     * @return string Anonymous ID
     */
    public function getResponseAnonymousId(string $studentId, ?string $createdAt = null): string
    {
        return $this->generateAnonymousId($studentId, $createdAt);
    }

    /**
     * Check if anonymization should be used for analytics
     * 
     * @return bool
     */
    public function useForAnalytics(): bool
    {
        return Config::get('privacy.anonymization.use_for_analytics', true);
    }

    /**
     * Check if anonymization should be used for reporting
     * 
     * @return bool
     */
    public function useForReporting(): bool
    {
        return Config::get('privacy.anonymization.use_for_reporting', true);
    }
}


