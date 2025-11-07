<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use App\Services\EncryptionService;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable implements CanResetPassword, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, CanResetPasswordTrait;

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'student_id',
        'first_name',
        'last_name',
        'year_level',
        'section',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'string',
        'year_level' => 'integer',
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
     * Encrypt student_id when setting (AES-256)
     * 
     * @param string|null $value
     * @return void
     */
    public function setStudentIdAttribute(?string $value): void
    {
        $this->attributes['student_id'] = !empty($value) 
            ? $this->encryptionService()->encrypt($value) 
            : $value;
    }

    /**
     * Decrypt student_id when getting
     * Handles both encrypted (new) and unencrypted (legacy) values
     * 
     * @param string|null $value
     * @return string|null
     */
    public function getStudentIdAttribute(?string $value): ?string
    {
        // Get raw value from attributes (bypasses accessor to avoid recursion)
        $rawValue = $this->attributes['student_id'] ?? $value;
        
        if (empty($rawValue)) {
            return null;
        }

        // Check if value is encrypted (Laravel's Crypt format starts with "eyJpdiI6")
        // Laravel encrypted strings are base64 encoded JSON objects
        $isEncrypted = preg_match('/^eyJpdiI6/', $rawValue);
        
        if (!$isEncrypted) {
            // Value is not encrypted (legacy data like "24-262830"), return as-is
            return $rawValue;
        }

        // Value appears encrypted, try to decrypt
        // Cache decrypted value to avoid repeated decryption
        $cacheKey = "user_student_id_{$this->id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($rawValue) {
            try {
                $decrypted = $this->encryptionService()->decrypt($rawValue);
                // If decryption returns null, the value might be corrupted
                // Return original value as fallback
                return $decrypted ?? $rawValue;
            } catch (\Exception $e) {
                // If decryption fails completely, return original value
                // This handles edge cases where encryption format changed
                \Illuminate\Support\Facades\Log::warning('Failed to decrypt student_id, returning original', [
                    'user_id' => $this->id,
                    'error' => $e->getMessage(),
                ]);
                return $rawValue;
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => 'string',
        ];
    }
}
