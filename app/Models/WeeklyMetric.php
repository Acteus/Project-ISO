<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * WeeklyMetric Model
 *
 * Stores weekly snapshots of ISO 21001 quality education metrics
 * for progress tracking and trend analysis.
 */
class WeeklyMetric extends Model
{
    protected $fillable = [
        'week_start_date',
        'week_end_date',
        'year',
        'week_number',
        'total_responses',
        'new_responses',
        'learner_needs_index',
        'satisfaction_score',
        'success_index',
        'safety_index',
        'wellbeing_index',
        'overall_satisfaction',
        'compliance_score',
        'compliance_percentage',
        'risk_level',
        'responses_by_track',
        'responses_by_grade',
        'responses_by_gender',
        'satisfaction_trend',
        'compliance_trend',
        'response_trend',
        'target_satisfaction',
        'target_compliance',
        'target_responses',
        'satisfaction_target_met',
        'compliance_target_met',
        'response_target_met',
        'key_insights',
        'recommendations',
    ];

    protected $casts = [
        'week_start_date' => 'date',
        'week_end_date' => 'date',
        'year' => 'integer',
        'week_number' => 'integer',
        'total_responses' => 'integer',
        'new_responses' => 'integer',
        'learner_needs_index' => 'decimal:2',
        'satisfaction_score' => 'decimal:2',
        'success_index' => 'decimal:2',
        'safety_index' => 'decimal:2',
        'wellbeing_index' => 'decimal:2',
        'overall_satisfaction' => 'decimal:2',
        'compliance_score' => 'decimal:2',
        'compliance_percentage' => 'decimal:2',
        'satisfaction_trend' => 'decimal:2',
        'compliance_trend' => 'decimal:2',
        'response_trend' => 'decimal:2',
        'target_satisfaction' => 'decimal:2',
        'target_compliance' => 'decimal:2',
        'target_responses' => 'integer',
        'satisfaction_target_met' => 'boolean',
        'compliance_target_met' => 'boolean',
        'response_target_met' => 'boolean',
        'responses_by_track' => 'array',
        'responses_by_grade' => 'array',
        'responses_by_gender' => 'array',
        'key_insights' => 'array',
        'recommendations' => 'array',
    ];

    /**
     * Get the week label for display
     */
    public function getWeekLabelAttribute()
    {
        return "Week {$this->week_number}, {$this->year}";
    }

    /**
     * Get the date range label for display
     */
    public function getDateRangeLabelAttribute()
    {
        return $this->week_start_date->format('M j') . ' - ' . $this->week_end_date->format('M j, Y');
    }

    /**
     * Check if all targets were met this week
     */
    public function getAllTargetsMetAttribute()
    {
        return $this->satisfaction_target_met && $this->compliance_target_met && $this->response_target_met;
    }

    /**
     * Scope for getting metrics by year
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope for getting metrics by week number
     */
    public function scopeByWeek($query, $weekNumber)
    {
        return $query->where('week_number', $weekNumber);
    }

    /**
     * Scope for getting recent weeks
     */
    public function scopeRecent($query, $weeks = 12)
    {
        return $query->orderBy('week_start_date', 'desc')->limit($weeks);
    }

    /**
     * Scope for getting metrics with trends
     */
    public function scopeWithTrends($query)
    {
        return $query->whereNotNull('satisfaction_trend');
    }
}
