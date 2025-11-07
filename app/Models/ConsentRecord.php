<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Consent Record Model
 * 
 * Tracks explicit consent with audit trail (GDPR & ISO 27001 compliant)
 * 
 * @package App\Models
 */
class ConsentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'ip_address',
        'consent_given',
        'consent_purpose',
        'consent_version',
        'expires_at',
        'revoked_at',
        'metadata',
    ];

    protected $casts = [
        'consent_given' => 'boolean',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Check if consent is currently valid
     * 
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->consent_given 
            && $this->revoked_at === null 
            && $this->expires_at > Carbon::now();
    }

    /**
     * Scope to get only valid consents
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeValid($query)
    {
        return $query->where('consent_given', true)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', Carbon::now());
    }

    /**
     * Scope to get expired consents
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', Carbon::now());
    }
}

