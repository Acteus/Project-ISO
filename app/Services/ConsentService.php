<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\ConsentRecord;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * Consent Management Service
 * 
 * Manages explicit consent validation with audit trail (GDPR & ISO 27001 compliant)
 * 
 * @package App\Services
 */
class ConsentService
{
    /**
     * Validate and record explicit consent
     * 
     * @param bool $consentGiven Whether consent was given
     * @param string|null $studentId Student ID (optional, for anonymous users)
     * @param string|null $ipAddress IP address
     * @param array $context Additional context (e.g., survey_id, purpose)
     * @return bool True if consent is valid
     * @throws \Exception If consent is required but not given
     */
    public function validateAndRecordConsent(
        bool $consentGiven,
        ?string $studentId = null,
        ?string $ipAddress = null,
        array $context = []
    ): bool {
        $requireExplicitConsent = Config::get('privacy.consent.require_explicit_consent', true);
        
        if ($requireExplicitConsent && !$consentGiven) {
            $this->auditConsentAction('consent_denied', $studentId, $ipAddress, $context);
            throw new \Exception('Explicit consent is required to process this data.');
        }

        // Record consent in database
        if ($consentGiven) {
            $this->recordConsent($studentId, $ipAddress, $context);
            $this->auditConsentAction('consent_given', $studentId, $ipAddress, $context);
        }

        return $consentGiven;
    }

    /**
     * Record consent in the database
     * 
     * @param string|null $studentId
     * @param string|null $ipAddress
     * @param array $context
     * @return ConsentRecord
     */
    public function recordConsent(?string $studentId, ?string $ipAddress, array $context = []): ConsentRecord
    {
        $consentRecord = ConsentRecord::create([
            'student_id' => $studentId,
            'ip_address' => $ipAddress,
            'consent_given' => true,
            'consent_purpose' => $context['purpose'] ?? 'survey_response',
            'consent_version' => $context['consent_version'] ?? '1.0',
            'expires_at' => now()->addDays(Config::get('privacy.consent.consent_validity_days', 365)),
            'metadata' => $context,
        ]);

        return $consentRecord;
    }

    /**
     * Check if consent is valid for a student
     * 
     * @param string|null $studentId
     * @param string $purpose Purpose of consent check
     * @return bool True if valid consent exists
     */
    public function hasValidConsent(?string $studentId, string $purpose = 'survey_response'): bool
    {
        if (empty($studentId)) {
            return false;
        }

        $consent = ConsentRecord::where('student_id', $studentId)
            ->where('consent_given', true)
            ->where('consent_purpose', $purpose)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        return $consent !== null;
    }

    /**
     * Revoke consent
     * 
     * @param string $studentId
     * @param string $purpose
     * @param string|null $ipAddress
     * @return bool
     */
    public function revokeConsent(string $studentId, string $purpose = 'survey_response', ?string $ipAddress = null): bool
    {
        // Mark existing consents as revoked
        $revoked = ConsentRecord::where('student_id', $studentId)
            ->where('consent_purpose', $purpose)
            ->where('consent_given', true)
            ->where('expires_at', '>', now())
            ->update([
                'consent_given' => false,
                'revoked_at' => now(),
            ]);

        if ($revoked > 0) {
            $this->auditConsentAction('consent_revoked', $studentId, $ipAddress, ['purpose' => $purpose]);
        }

        return $revoked > 0;
    }

    /**
     * Audit consent actions
     * 
     * @param string $action Action type (consent_given, consent_denied, consent_revoked)
     * @param string|null $studentId
     * @param string|null $ipAddress
     * @param array $context
     * @return void
     */
    protected function auditConsentAction(string $action, ?string $studentId, ?string $ipAddress, array $context = []): void
    {
        if (!Config::get('privacy.consent.audit_consent_changes', true)) {
            return;
        }

        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'description' => "Consent action: {$action}",
                'ip_address' => $ipAddress,
                'new_values' => [
                    'student_id' => $studentId ? '***REDACTED***' : null,
                    'context' => $context,
                    'timestamp' => now()->toIso8601String(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to audit consent action', [
                'action' => $action,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get consent history for a student
     * 
     * @param string|null $studentId
     * @param string|null $purpose
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getConsentHistory(?string $studentId, ?string $purpose = null)
    {
        if (!$studentId) {
            return collect([]);
        }

        $query = ConsentRecord::where('student_id', $studentId)
            ->orderBy('created_at', 'desc');

        if ($purpose) {
            $query->where('consent_purpose', $purpose);
        }

        return $query->get();
    }
}

