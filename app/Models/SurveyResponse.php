<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SurveyResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_number',
        'program',
        'year_level',
        'course_content_rating',
        'facilities_rating',
        'support_services_rating',
        'overall_satisfaction',
        'comments',
        'consent_given',
        'ip_address',
    ];

    protected $casts = [
        'course_content_rating' => 'integer',
        'facilities_rating' => 'integer',
        'support_services_rating' => 'integer',
        'overall_satisfaction' => 'integer',
        'year_level' => 'integer',
        'consent_given' => 'boolean',
    ];

    protected $hidden = [
        'student_number',
        'comments',
        'ip_address',
    ];

    // Encrypt sensitive data before saving
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->student_number) {
                $model->student_number = Crypt::encryptString($model->student_number);
            }
            if ($model->comments) {
                $model->comments = Crypt::encryptString($model->comments);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('student_number')) {
                $model->student_number = Crypt::encryptString($model->student_number);
            }
            if ($model->isDirty('comments')) {
                $model->comments = Crypt::encryptString($model->comments);
            }
        });
    }

    // Decrypt sensitive data when retrieving
    public function getStudentNumberAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getCommentsAttribute($value)
    {
        if (!$value) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Generate anonymous ID for analytics
    public function getAnonymousIdAttribute()
    {
        return hash('sha256', $this->student_number . $this->created_at);
    }
}
