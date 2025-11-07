# Consent Implementation Summary

## Where Consent is Collected

### ✅ **Recommended: Before Survey Submission (Survey Form)**

**Location:** `resources/views/survey/form.blade.php`

- Consent checkbox appears on the **last step** of the survey (before submit button)
- **Required checkbox** - users must check it to submit
- Shows detailed privacy information (GDPR & ISO 27001 compliance)
- Explicit consent is captured and validated before submission

**Why this approach:**
- ✅ Consent is collected at the point of data collection (GDPR best practice)
- ✅ Users have context about what data they're providing
- ✅ Can't submit without consent (enforced by backend validation)
- ✅ Clear, explicit consent with full information

### ✅ **Informational Notice: Landing Page**

**Location:** `resources/views/survey/landing.blade.php`

- Informational privacy notice added to landing page
- Explains data protection measures
- Links to privacy policy
- Sets expectations before users start the survey

**Why this approach:**
- ✅ Users are informed before starting
- ✅ Transparent about data handling
- ✅ Builds trust

### ❌ **Not Recommended: Registration**

**Why not at registration:**
- Registration consent would be too early (before seeing what data is collected)
- Survey-specific consent is more appropriate
- Users may forget what they consented to by the time they take the survey
- GDPR requires consent at the point of data collection

## Implementation Details

### 1. Survey Form Consent Section

**File:** `resources/views/survey/form.blade.php`

- Consent section appears when user reaches the last step
- Includes:
  - Privacy shield icon
  - Detailed explanation of data protection
  - Required checkbox
  - Clear consent statement

### 2. JavaScript Updates

**File:** `public/js/survey.js`

- Updated `updateNavigationButtons()` to show consent section on last step
- Updated `mapFieldsForLaravelAPI()` to read consent checkbox value
- Consent value is sent to backend for validation

### 3. Backend Validation

**File:** `app/Http/Controllers/SurveyController.php`

- `ConsentService` validates consent before processing
- Creates consent record in database
- Logs consent action to audit trail
- Returns 403 error if consent not given

### 4. Database

**Migration:** `database/migrations/*_create_consent_records_table.php`

- Stores consent records with:
  - Student ID (encrypted)
  - IP address
  - Consent purpose
  - Consent version
  - Expiration date
  - Revocation timestamp
  - Metadata

## Seeder Updates

**File:** `database/factories/SurveyResponseFactory.php`

✅ **Already Updated:**
- `consent_given => true` is already set in the factory
- Seeders will create responses with consent

**No changes needed** - factory already includes consent.

## Cloudways Deployment Commands

### Step 1: Pull Latest Code
```bash
git pull origin deployment/laravel-cloud
```

### Step 2: Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### Step 3: Run Migrations
```bash
php artisan migrate --force
```

### Step 4: Clear and Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Verify
```bash
# Test consent flow
php artisan tinker
>>> App\Models\ConsentRecord::count()
```

## Environment Variables for Cloudways

Add to `.env` file:

```env
# Required: Application Key (should already exist)
APP_KEY=base64:YOUR_32_CHARACTER_KEY_HERE

# Optional: Custom Privacy Encryption Key (defaults to APP_KEY)
# Only set if you want a different key for privacy encryption
# If not set, APP_KEY will be used (recommended)
PRIVACY_ENCRYPTION_KEY=

# Optional: Custom Anonymization Salt (defaults to APP_KEY)
# Only set if you want a different salt for anonymization
# If not set, APP_KEY will be used (recommended)
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

### Important Notes:

1. **APP_KEY is Required:**
   - Must be set (Laravel requirement)
   - Generate with: `php artisan key:generate`
   - **Never lose this key** - encrypted data becomes unrecoverable

2. **PRIVACY_ENCRYPTION_KEY (Optional):**
   - If not set, uses `APP_KEY` (recommended for simplicity)
   - Only set if you need separate encryption keys
   - Must be 32 characters base64 encoded

3. **ANONYMIZATION_SALT (Optional):**
   - If not set, uses `APP_KEY` (recommended)
   - Only set if you need a different salt
   - Can be any string (recommended: 32+ characters)

## Testing Checklist

After deployment, test:

1. ✅ Visit landing page - see privacy notice
2. ✅ Start survey - fill out all steps
3. ✅ On last step - consent section appears
4. ✅ Try to submit without consent - should fail
5. ✅ Check consent checkbox - submit should work
6. ✅ Check `consent_records` table - new entry created
7. ✅ Check `audit_logs` - consent action logged

## Summary

**Consent is collected:**
- ✅ **On the survey form** (last step, before submit) - **PRIMARY**
- ✅ **Informational notice on landing page** - **SECONDARY**

**Not collected at registration** - this is the correct GDPR-compliant approach.

All seeders already include `consent_given = true`, so no seeder changes needed.

