# Email Authentication and Password Reset Implementation

## Overview
This document describes the implementation of email-based authentication with password reset functionality for the Jose Rizal University ISO 21001 Survey System.

## Features Implemented

### 1. Email Validation with Domain Restriction
- Students must register using their official JRU email address with `@my.jru.edu` domain
- Validation enforced at both client-side (JavaScript) and server-side (Laravel)
- Custom validation messages for better user experience

### 2. Enhanced Registration Form
- Added email field to student registration
- Email validation using regex: `/^[a-zA-Z0-9._%+-]+@my\.jru\.edu$/`
- Password minimum length increased to 8 characters (Laravel standard)
- Unique constraint on both email and student ID

### 3. Forgot Password Functionality
- Beautiful UI matching the existing design system
- Email-based password reset following Laravel conventions
- Secure token generation and validation
- Custom email notifications

## Files Created/Modified

### New Files Created:
1. **app/Http/Controllers/Auth/ForgotPasswordController.php**
   - Handles password reset link requests
   - Manages password reset form submission

2. **app/Notifications/ResetPasswordNotification.php**
   - Custom email notification for password resets
   - Branded for Jose Rizal University

3. **resources/views/auth/forgot-password.blade.php**
   - Beautiful UI for requesting password reset
   - Matches existing design system

4. **resources/views/auth/reset-password.blade.php**
   - Form for resetting password with token
   - Client-side validation

5. **app/Http/Requests/StudentRegistrationRequest.php**
   - Form request validation for student registration
   - Centralized validation logic

6. **database/migrations/2025_10_29_000000_add_email_verified_to_users_table.php**
   - Migration to ensure email verification column exists

### Modified Files:
1. **app/Models/User.php**
   - Implemented `CanResetPassword` interface
   - Added custom password reset notification

2. **app/Http/Controllers/StudentController.php**
   - Updated registration to accept and validate email
   - Enhanced validation rules

3. **resources/views/student/register.blade.php**
   - Added email input field
   - Updated validation messages

4. **resources/views/student/login.blade.php**
   - Added link to forgot password page

5. **routes/web.php**
   - Added password reset routes

## Routes Added

```php
// Password Reset Routes
Route::prefix('password')->name('password.')->group(function () {
    Route::get('/forgot', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('request');
    Route::post('/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('email');
    Route::get('/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('reset');
    Route::post('/reset', [ForgotPasswordController::class, 'reset'])->name('update');
});
```

## Database Changes

The system uses Laravel's built-in `password_reset_tokens` table:
- `email` (primary key)
- `token` (hashed)
- `created_at` (timestamp)

## Configuration Required

### Environment Variables (.env)
```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io  # Or your SMTP server
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@my.jru.edu
MAIL_FROM_NAME="Jose Rizal University"

# For production, use actual SMTP settings:
# MAIL_HOST=smtp.gmail.com
# MAIL_PORT=587
# MAIL_USERNAME=your-email@gmail.com
# MAIL_PASSWORD=your-app-password
```

## Setup Instructions

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Configure Mail Settings
Update your `.env` file with appropriate mail settings:
- For development: Use Mailtrap or log driver
- For production: Configure actual SMTP server

### 3. Test Email Functionality
```bash
# Test email configuration
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@my.jru.edu')->subject('Test'); });
```

### 4. Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## Usage Flow

### Registration
1. Student visits `/student/register`
2. Fills in first name, last name, **email (@my.jru.edu)**, year level, section, student ID, and password
3. Email is validated for correct domain
4. Account is created and student is logged in

### Forgot Password
1. Student clicks "Forgot password?" on login page
2. Redirected to `/password/forgot`
3. Enters their `@my.jru.edu` email address
4. Receives email with reset link (valid for 60 minutes)
5. Clicks link to `/password/reset/{token}?email={email}`
6. Enters new password (minimum 8 characters)
7. Password is reset and redirected to login

## Security Features

1. **Email Domain Restriction**: Only `@my.jru.edu` emails accepted
2. **Token Expiration**: Reset tokens expire after 60 minutes
3. **Rate Limiting**: Laravel's built-in throttling prevents abuse
4. **CSRF Protection**: All forms protected with CSRF tokens
5. **Password Hashing**: Using bcrypt (Laravel default)
6. **Secure Token Generation**: Cryptographically secure random tokens

## Validation Rules

### Registration Email
- Required
- Valid email format
- Unique in database
- Must match regex: `/^[a-zA-Z0-9._%+-]+@my\.jru\.edu$/`

### Password
- Minimum 8 characters
- Must be confirmed
- Follows Laravel password rules

## UI/UX Features

- Consistent design with existing pages
- Real-time client-side validation
- AJAX form submission (no page reload)
- Clear error messages
- Loading states on buttons
- Responsive design
- Animations and transitions

## Testing

### Manual Testing Checklist
- [ ] Register with valid @my.jru.edu email
- [ ] Try to register with non-JRU email (should fail)
- [ ] Try to register with duplicate email (should fail)
- [ ] Request password reset with valid email
- [ ] Request password reset with invalid email
- [ ] Use password reset link
- [ ] Try to use expired token
- [ ] Reset password successfully
- [ ] Login with new password

### Email Testing
For development, you can use:
- **Mailtrap.io**: Free email testing service
- **Log driver**: Emails written to `storage/logs/laravel.log`
- **MailHog**: Local SMTP testing server

## Troubleshooting

### Emails Not Sending
1. Check `.env` mail configuration
2. Verify SMTP credentials
3. Check `storage/logs/laravel.log` for errors
4. Test with log driver first: `MAIL_MAILER=log`

### Email Validation Failing
1. Ensure email matches exactly: `something@my.jru.edu`
2. Check for spaces or special characters
3. Verify regex pattern in validation

### Token Expired/Invalid
1. Tokens expire after 60 minutes (configurable in `config/auth.php`)
2. Check `password_reset_tokens` table for token existence
3. Ensure user exists in database

## Future Enhancements

1. **Email Verification**: Require users to verify their email before accessing system
2. **Two-Factor Authentication**: Add 2FA for enhanced security
3. **Password Strength Meter**: Visual feedback on password strength
4. **Account Recovery**: Additional recovery options (security questions, SMS)
5. **Password History**: Prevent reusing recent passwords

## Support

For issues or questions, contact the development team or refer to Laravel documentation:
- [Laravel Password Reset Documentation](https://laravel.com/docs/11.x/passwords)
- [Laravel Mail Documentation](https://laravel.com/docs/11.x/mail)
