# Data Privacy Deployment Guide for Cloudways

This guide covers deploying the GDPR & ISO 27001 data privacy features to Cloudways.

## Pre-Deployment Checklist

### 1. Environment Variables

Add/update these variables in Cloudways `.env` file:

```env
# Required: Application Key (should already exist)
APP_KEY=base64:YOUR_32_CHARACTER_KEY_HERE

# Optional: Custom Privacy Encryption Key (defaults to APP_KEY if not set)
# Generate with: php artisan key:generate --show
PRIVACY_ENCRYPTION_KEY=

# Optional: Custom Anonymization Salt (defaults to APP_KEY if not set)
ANONYMIZATION_SALT=

# Data Retention Periods (in days)
# Default: 2555 days (~7 years, ISO 21001 recommendation)
DATA_RETENTION_DAYS=2555
SURVEY_RETENTION_DAYS=2555
AUDIT_LOG_RETENTION_DAYS=2555
CONSENT_RETENTION_DAYS=2555

# Optional: Backup before deletion
BACKUP_BEFORE_DELETION=false
```

### 2. Generate Encryption Keys (if needed)

If you want separate encryption keys:

```bash
# Generate a new encryption key
php artisan key:generate --show

# Copy the output and set it as PRIVACY_ENCRYPTION_KEY in .env
# Or use the same APP_KEY (recommended for simplicity)
```

**Note:** If `PRIVACY_ENCRYPTION_KEY` is not set, the system will use `APP_KEY` automatically.

## Deployment Commands (Run on Cloudways)

### Step 1: Pull Latest Code
```bash
git pull origin deployment/laravel-cloud
# or your main branch
```

### Step 2: Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### Step 3: Run Database Migrations
```bash
php artisan migrate --force
```

This will create:
- `consent_records` table for consent tracking

### Step 4: Clear and Cache Configuration
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches (production optimization)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Verify Configuration
```bash
# Check if privacy config is loaded
php artisan tinker
>>> config('privacy.encryption.enabled')
=> true
>>> exit
```

### Step 6: Test Data Retention (Optional - Dry Run)
```bash
# View retention statistics
php artisan data:retention-cleanup --stats

# Test cleanup without deleting (dry run)
php artisan data:retention-cleanup --dry-run
```

### Step 7: Set Up Scheduled Cleanup (Optional)

Add to `app/Console/Kernel.php` in the `schedule()` method:

```php
protected function schedule(Schedule $schedule)
{
    // Run data retention cleanup weekly on Sundays at 2 AM
    $schedule->command('data:retention-cleanup')
        ->weekly()
        ->sundays()
        ->at('02:00')
        ->timezone('Asia/Manila');
}
```

Then ensure cron is running:
```bash
# Check if cron is configured (Cloudways usually handles this)
crontab -l
```

## Post-Deployment Verification

### 1. Test Consent Flow
1. Visit the survey landing page
2. Click "Start Survey"
3. Fill out the survey
4. Verify consent checkbox is visible and required
5. Submit survey
6. Check `consent_records` table for new entry

### 2. Test Encryption
```bash
php artisan tinker
>>> $response = App\Models\SurveyResponse::first();
>>> $response->student_id; // Should be decrypted automatically
>>> $response->getAttributes()['student_id']; // Should show encrypted value
```

### 3. Test Anonymization
```bash
php artisan tinker
>>> $response = App\Models\SurveyResponse::first();
>>> $response->anonymous_id; // Should return SHA-256 hash
```

### 4. Check Audit Logs
```bash
php artisan tinker
>>> App\Models\AuditLog::where('action', 'like', '%consent%')->latest()->get();
```

## Important Notes

### Encryption Key Management

⚠️ **CRITICAL:** Never lose your `APP_KEY` or `PRIVACY_ENCRYPTION_KEY`!

- If you lose the key, all encrypted data becomes unrecoverable
- Keep backups of your `.env` file in a secure location
- Use Cloudways' environment variable management for production
- Consider using a secrets manager for production environments

### Data Migration

If you have existing data:

1. **Existing Survey Responses**: 
   - Old data will remain unencrypted until updated
   - New submissions will be automatically encrypted
   - To encrypt existing data, you'll need a migration script

2. **Consent Records**:
   - Existing survey responses won't have consent records
   - Only new submissions will create consent records
   - This is acceptable for GDPR compliance going forward

### Performance Considerations

- Encryption/decryption adds minimal overhead (~1-2ms per field)
- Caching reduces repeated decryption operations
- Anonymization is fast (SHA-256 hashing)
- Data retention cleanup should run during low-traffic hours

## Troubleshooting

### Issue: "Encryption failed" errors
**Solution:** Check that `APP_KEY` is set and valid (32 characters base64)

### Issue: Consent validation failing
**Solution:** 
- Verify `consent_given` is being sent from frontend
- Check `config/privacy.php` - `consent.require_explicit_consent` should be `true`
- Check audit logs for consent-related errors

### Issue: Anonymous IDs not generating
**Solution:**
- Verify `ANONYMIZATION_SALT` or `APP_KEY` is set
- Check that `student_id` exists and is not null

### Issue: Data retention not working
**Solution:**
- Verify `privacy.retention.enabled` is `true` in config
- Check retention period settings
- Run with `--dry-run` first to see what would be deleted

## Rollback Plan

If you need to rollback:

1. **Disable Privacy Features:**
   ```bash
   # In .env, set:
   PRIVACY_ENCRYPTION_KEY=
   # Or comment out privacy config usage
   ```

2. **Revert Migrations:**
   ```bash
   php artisan migrate:rollback --step=1
   ```

3. **Restore Previous Code:**
   ```bash
   git checkout <previous-commit>
   composer install
   php artisan config:clear
   php artisan cache:clear
   ```

## Support

For issues or questions:
- Check `storage/logs/laravel.log` for errors
- Review `docs/DATA_PRIVACY_IMPLEMENTATION.md` for implementation details
- Check audit logs for privacy-related actions

