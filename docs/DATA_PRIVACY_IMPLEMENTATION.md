# Data Privacy Implementation (GDPR & ISO 27001)

This document describes the data privacy features implemented to ensure GDPR and ISO 27001 compliance.

## Overview

The codebase now includes comprehensive data privacy features:

1. **AES-256 Encryption** for sensitive student data
2. **SHA-256 Anonymization** for analytics and reporting
3. **Consent Management** with explicit validation and audit trail
4. **Data Minimization** - only essential ISO 21001 metrics collected
5. **Data Retention Policies** with configurable periods and automated cleanup

## Implementation Details

### 1. Encryption (AES-256)

**Service:** `App\Services\EncryptionService`

- Encrypts sensitive fields: `student_id`, `positive_aspects`, `improvement_suggestions`, `additional_comments`
- Uses Laravel's Crypt facade with AES-256-CBC cipher
- Automatic encryption/decryption via model mutators and accessors
- Caching for performance optimization

**Usage:**
```php
$encryptionService = app(EncryptionService::class);
$encrypted = $encryptionService->encrypt($sensitiveData);
$decrypted = $encryptionService->decrypt($encrypted);
```

**Models Updated:**
- `SurveyResponse` - encrypts student_id and all comment fields
- `User` - encrypts student_id

### 2. Anonymization (SHA-256)

**Service:** `App\Services\AnonymizationService`

- Generates SHA-256 anonymous IDs for analytics and reporting
- One-way hashing (cannot be reversed)
- Consistent anonymous IDs for the same student across sessions
- Used automatically in analytics endpoints

**Usage:**
```php
$anonymizationService = app(AnonymizationService::class);
$anonymousId = $anonymizationService->generateAnonymousId($studentId, $additionalData);
```

**Configuration:**
- `config/privacy.php` - `anonymization.salt` (uses APP_KEY by default)

### 3. Consent Management

**Service:** `App\Services\ConsentService`

- Explicit consent validation required before data processing
- Consent records stored in `consent_records` table
- Full audit trail via `AuditLog` model
- Consent expiration and revocation support

**Features:**
- Validates consent before survey submission
- Records consent with purpose, version, and expiration
- Tracks consent history
- Supports consent revocation

**Database Migration:**
- `database/migrations/*_create_consent_records_table.php`

**Usage:**
```php
$consentService = app(ConsentService::class);
$consentService->validateAndRecordConsent(
    $consentGiven,
    $studentId,
    $ipAddress,
    ['purpose' => 'survey_response', 'consent_version' => '1.0']
);
```

### 4. Data Minimization

**Service:** `App\Services\DataMinimizationService`

- Validates that only essential ISO 21001 metrics are collected
- Rejects unauthorized fields automatically
- Configurable allowed fields list

**Allowed Fields (ISO 21001 Essential Metrics):**
- Student Information (minimal): student_id, track, grade_level, academic_year, semester, gender
- ISO 21001 Learner Needs Assessment (4 metrics)
- ISO 21001 Learner Satisfaction Metrics (4 metrics)
- ISO 21001 Learner Success Indicators (4 metrics)
- ISO 21001 Learner Safety Assessment (4 metrics)
- ISO 21001 Learner Wellbeing Metrics (4 metrics)
- Overall Satisfaction and Feedback
- Indirect Metrics (ISO 21001 compliant)

**Usage:**
```php
$minimizationService = app(DataMinimizationService::class);
$check = $minimizationService->validateDataMinimization($data);
$filtered = $minimizationService->filterAllowedFields($data);
```

### 5. Data Retention Policies

**Service:** `App\Services\DataRetentionService`

- Configurable retention periods (default: 2555 days ~7 years)
- Automated cleanup via Artisan command
- Separate retention periods for different data types
- Anonymization before deletion (optional)

**Configuration:**
```php
// config/privacy.php
'retention' => [
    'enabled' => true,
    'default_retention_days' => 2555,
    'survey_responses_retention_days' => 2555,
    'audit_logs_retention_days' => 2555,
    'consent_records_retention_days' => 2555,
]
```

**Artisan Command:**
```bash
# View retention statistics
php artisan data:retention-cleanup --stats

# Dry run (see what would be deleted)
php artisan data:retention-cleanup --dry-run

# Actually delete expired data
php artisan data:retention-cleanup
```

## Configuration

All privacy settings are configured in `config/privacy.php`:

```php
return [
    'encryption' => [...],
    'anonymization' => [...],
    'consent' => [...],
    'data_minimization' => [...],
    'retention' => [...],
];
```

### Environment Variables

Add to `.env`:
```env
# Optional: Custom encryption key (defaults to APP_KEY)
PRIVACY_ENCRYPTION_KEY=

# Optional: Custom anonymization salt (defaults to APP_KEY)
ANONYMIZATION_SALT=

# Data retention periods (in days)
DATA_RETENTION_DAYS=2555
SURVEY_RETENTION_DAYS=2555
AUDIT_LOG_RETENTION_DAYS=2555
CONSENT_RETENTION_DAYS=2555

# Optional: Backup before deletion
BACKUP_BEFORE_DELETION=false
```

## Database Migrations

Run the following migration to create the consent_records table:

```bash
php artisan migrate
```

This will create:
- `consent_records` table for tracking consent with audit trail

## Integration Points

### Survey Submission

The `SurveyController::submitResponse()` method now:
1. Validates data minimization
2. Validates and records explicit consent
3. Encrypts sensitive fields automatically (via model)
4. Logs all actions to audit trail

### Analytics

Analytics endpoints automatically:
- Use anonymous IDs instead of student IDs
- Hide sensitive fields
- Comply with GDPR anonymization requirements

## Compliance Checklist

✅ **Encryption**: AES-256 encryption for sensitive student data (student_id, comments)
✅ **Anonymization**: SHA-256 anonymous IDs for analytics and reporting
✅ **Consent Management**: Explicit consent validation with audit trail
✅ **Data Minimization**: Only essential ISO 21001 metrics collected
✅ **Retention Policies**: Configurable data retention periods with automated cleanup

## Testing

To test the implementation:

1. **Test Encryption:**
   ```php
   $response = SurveyResponse::create([...]);
   // student_id is automatically encrypted
   $decrypted = $response->student_id; // Automatically decrypted
   ```

2. **Test Consent:**
   - Submit survey without consent → Should fail with 403
   - Submit survey with consent → Should succeed and create consent record

3. **Test Data Minimization:**
   - Submit survey with extra fields → Should reject unauthorized fields

4. **Test Retention:**
   ```bash
   php artisan data:retention-cleanup --stats
   php artisan data:retention-cleanup --dry-run
   ```

## Maintenance

### Scheduled Cleanup

Add to `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Run data retention cleanup weekly
    $schedule->command('data:retention-cleanup')
        ->weekly()
        ->sundays()
        ->at('02:00');
}
```

### Monitoring

Monitor the following:
- Consent rates (via `ConsentRecord` model)
- Data retention statistics (via `data:retention-cleanup --stats`)
- Audit logs for privacy-related actions
- Encryption/decryption errors in logs

## Security Notes

1. **Encryption Keys**: Never commit encryption keys to version control
2. **Anonymization Salt**: Use a strong, unique salt for anonymization
3. **Consent Records**: Keep consent records for the full retention period
4. **Audit Logs**: Maintain audit logs for compliance audits
5. **Data Deletion**: Ensure proper backup before running retention cleanup

## Support

For questions or issues related to data privacy implementation:
- Review `config/privacy.php` for configuration options
- Check `app/Services/` for service implementations
- Review audit logs for privacy-related actions
- Consult GDPR and ISO 27001 documentation for compliance requirements


