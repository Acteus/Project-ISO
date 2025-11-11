<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PerformanceMetric extends Model
{
    protected $fillable = [
        'type',
        'category',
        'operation',
        'duration_ms',
        'success',
        'error',
        'rows_affected',
        'query',
        'metadata',
        'timestamp',
    ];

    protected $casts = [
        'duration_ms' => 'float',
        'success' => 'boolean',
        'rows_affected' => 'integer',
        'timestamp' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Scope for AI service metrics
     */
    public function scopeAIService($query)
    {
        return $query->where('type', 'ai_service');
    }

    /**
     * Scope for analytics query metrics
     */
    public function scopeAnalyticsQuery($query)
    {
        return $query->where('type', 'analytics_query');
    }

    /**
     * Scope for successful operations
     */
    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    /**
     * Scope for failed operations
     */
    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }

    /**
     * Scope for slow operations
     */
    public function scopeSlow($query, $thresholdMs = 1000)
    {
        return $query->where('duration_ms', '>', $thresholdMs);
    }
}
