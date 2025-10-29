<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QrCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'target_url',
        'file_path',
        'format',
        'size',
        'foreground_color',
        'background_color',
        'track',
        'grade_level',
        'section',
        'academic_year',
        'semester',
        'scan_count',
        'scan_analytics',
        'version',
        'expires_at',
        'is_active',
        'custom_options',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'scan_analytics' => 'array',
        'custom_options' => 'array',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'size' => 'integer',
        'scan_count' => 'integer',
    ];

    /**
     * Get the full URL to the QR code file.
     *
     * @return string|null
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    /**
     * Check if the QR code is expired.
     *
     * @return bool
     */
    public function getIsExpiredAttribute()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Scope to get only active QR codes.
     *
     * @param \Illuminate\Database\Eloquent.builder $query
     * @return \Illuminate\Database\Eloquent\builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get QR codes by track.
     *
     * @param \Illuminate\Database\Eloquent.builder $query
     * @param string $track
     * @return \Illuminate\Database\Eloquent\builder
     */
    public function scopeByTrack($query, $track)
    {
        return $query->where('track', $track);
    }

    /**
     * Scope to get QR codes by grade level.
     *
     * @param \Illuminate\Database\Eloquent.builder $query
     * @param string $gradeLevel
     * @return \Illuminate\Database\Eloquent\builder
     */
    public function scopeByGradeLevel($query, $gradeLevel)
    {
        return $query->where('grade_level', $gradeLevel);
    }

    /**
     * Scope to get QR codes by section.
     *
     * @param \Illuminate\Database\Eloquent.builder $query
     * @param string $section
     * @return \Illuminate\Database\Eloquent\builder
     */
    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Increment the scan count.
     *
     * @param array $analyticsData
     * @return void
     */
    public function recordScan($analyticsData = [])
    {
        $this->increment('scan_count');

        if (!empty($analyticsData)) {
            $currentAnalytics = $this->scan_analytics ?? [];
            $currentAnalytics[] = array_merge([
                'timestamp' => now(),
                'user_agent' => request()->userAgent(),
                'ip_address' => request()->ip(),
            ], $analyticsData);

            // Keep only last 100 scan records to prevent database bloat
            if (count($currentAnalytics) > 100) {
                $currentAnalytics = array_slice($currentAnalytics, -100);
            }

            $this->update(['scan_analytics' => $currentAnalytics]);
        }
    }

    /**
     * Get the public URL for the QR code.
     *
     * @return string
     */
    public function getPublicUrl()
    {
        return route('qr.show', $this->id);
    }

    /**
     * Boot method for the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate target URL if not provided
        static::creating(function ($qrCode) {
            if (!$qrCode->target_url) {
                $qrCode->target_url = route('welcome');
            }

            if (!$qrCode->academic_year) {
                $qrCode->academic_year = date('Y') . '-' . (date('Y') + 1);
            }
        });
    }
}
