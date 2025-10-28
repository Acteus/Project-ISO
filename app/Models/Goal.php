<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Goal Model
 *
 * Manages ISO 21001 quality education goals and targets
 * for continuous improvement tracking.
 */
class Goal extends Model
{
    protected $fillable = [
        'name',
        'description',
        'metric_type',
        'target_value',
        'current_value',
        'target_date',
        'status',
        'priority',
        'progress_history',
        'notes',
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'target_date' => 'date',
        'priority' => 'integer',
        'progress_history' => 'array',
    ];

    /**
     * Get the progress percentage towards the goal
     */
    public function getProgressPercentageAttribute()
    {
        if (!$this->current_value || $this->target_value == 0) {
            return 0;
        }

        return min(100, round(($this->current_value / $this->target_value) * 100, 1));
    }

    /**
     * Check if the goal is achieved
     */
    public function getIsAchievedAttribute()
    {
        return $this->current_value >= $this->target_value;
    }

    /**
     * Check if the goal is overdue
     */
    public function getIsOverdueAttribute()
    {
        return $this->target_date && $this->target_date->isPast() && !$this->is_achieved;
    }

    /**
     * Get priority label
     */
    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            1 => 'Low',
            2 => 'Medium',
            3 => 'High',
            4 => 'Critical',
            default => 'Unknown'
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'active' => 'Active',
            'achieved' => 'Achieved',
            'expired' => 'Expired',
            'cancelled' => 'Cancelled',
            default => 'Unknown'
        };
    }

    /**
     * Scope for active goals
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for achieved goals
     */
    public function scopeAchieved($query)
    {
        return $query->where('status', 'achieved');
    }

    /**
     * Scope for overdue goals
     */
    public function scopeOverdue($query)
    {
        return $query->where('target_date', '<', now())
                    ->where('status', 'active')
                    ->whereRaw('current_value < target_value');
    }

    /**
     * Scope by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope by metric type
     */
    public function scopeByMetricType($query, $metricType)
    {
        return $query->where('metric_type', $metricType);
    }

    /**
     * Update progress and history
     */
    public function updateProgress($newValue, $notes = null)
    {
        $oldValue = $this->current_value;

        $this->current_value = $newValue;

        // Add to progress history
        $history = $this->progress_history ?? [];
        $history[] = [
            'date' => now()->toDateString(),
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'change' => $newValue - $oldValue,
            'notes' => $notes,
        ];

        $this->progress_history = $history;

        // Check if goal is achieved
        if ($this->is_achieved && $this->status === 'active') {
            $this->status = 'achieved';
        }

        $this->save();
    }
}
