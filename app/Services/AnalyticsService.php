<?php

namespace App\Services;

use App\Models\SurveyResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Analytics Service - Simplified & Accurate
 *
 * All calculations are straightforward averages from survey_responses table.
 * No complex nested logic, everything is traceable to the source data.
 */
class AnalyticsService
{
    /**
     * Get comprehensive analytics summary
     * Single source of truth for all dashboard metrics
     */
    public function getAnalyticsSummary($filters = [])
    {
        $query = $this->applyFilters(SurveyResponse::query(), $filters);
        $responses = $query->get();

        // Return empty structure if no data
        if ($responses->isEmpty()) {
            return [
                'has_data' => false,
                'total_responses' => 0,
                'message' => 'No survey responses found for the selected filters.'
            ];
        }

        return [
            'has_data' => true,
            'total_responses' => $responses->count(),
            'date_range' => $this->getDateRange($responses),

            // Core ISO 21001 Indices (5 dimensions)
            'iso_indices' => $this->calculateISOIndices($responses),

            // Overall metrics
            'overall' => [
                'satisfaction' => round($responses->avg('overall_satisfaction'), 2),
                'compliance_score' => $this->calculateComplianceScore($responses),
            ],

            // Distribution breakdowns
            'distribution' => [
                'by_grade' => $this->getDistributionByGrade($responses),
                'by_gender' => $this->getDistributionByGender($responses),
                'by_semester' => $this->getDistributionBySemester($responses),
            ],

            // Time-based metrics
            'trends' => [
                'weekly_count' => $this->getWeeklyResponseCount($filters),
                'monthly_average' => $this->getMonthlyAverages($filters),
            ],

            // Compliance & Risk
            'compliance' => $this->getComplianceAssessment($responses),

            // Response metadata
            'metadata' => [
                'filters_applied' => $this->getAppliedFilters($filters),
                'date_collected' => [
                    'earliest' => $responses->min('created_at'),
                    'latest' => $responses->max('created_at'),
                ],
            ],
        ];
    }

    /**
     * Calculate ISO 21001 5 Core Indices
     * Each index is the average of 4 related questions (1-5 scale)
     */
    private function calculateISOIndices($responses)
    {
        return [
            'learner_needs' => round((
                $responses->avg('curriculum_relevance_rating') +
                $responses->avg('learning_pace_appropriateness') +
                $responses->avg('individual_support_availability') +
                $responses->avg('learning_style_accommodation')
            ) / 4, 2),

            'satisfaction' => round((
                $responses->avg('teaching_quality_rating') +
                $responses->avg('learning_environment_rating') +
                $responses->avg('peer_interaction_satisfaction') +
                $responses->avg('extracurricular_satisfaction')
            ) / 4, 2),

            'success' => round((
                $responses->avg('academic_progress_rating') +
                $responses->avg('skill_development_rating') +
                $responses->avg('critical_thinking_improvement') +
                $responses->avg('problem_solving_confidence')
            ) / 4, 2),

            'safety' => round((
                $responses->avg('physical_safety_rating') +
                $responses->avg('psychological_safety_rating') +
                $responses->avg('bullying_prevention_effectiveness') +
                $responses->avg('emergency_preparedness_rating')
            ) / 4, 2),

            'wellbeing' => round((
                $responses->avg('mental_health_support_rating') +
                $responses->avg('stress_management_support') +
                $responses->avg('physical_health_support') +
                $responses->avg('overall_wellbeing_rating')
            ) / 4, 2),
        ];
    }

    /**
     * Calculate overall compliance score (average of all 5 indices)
     */
    private function calculateComplianceScore($responses)
    {
        $indices = $this->calculateISOIndices($responses);
        $average = array_sum($indices) / count($indices);
        return round($average, 2);
    }

    /**
     * Get compliance risk assessment
     */
    private function getComplianceAssessment($responses)
    {
        $score = $this->calculateComplianceScore($responses);

        // Risk thresholds
        if ($score >= 4.0) {
            $risk_level = 'Low';
            $risk_color = 'green';
            $recommendations = [
                'Maintain current quality standards',
                'Continue regular monitoring and feedback collection',
                'Share best practices with other programs',
            ];
        } elseif ($score >= 3.5) {
            $risk_level = 'Medium';
            $risk_color = 'yellow';
            $recommendations = [
                'Review areas with scores below 4.0',
                'Implement targeted improvement initiatives',
                'Increase frequency of student feedback sessions',
                'Consider additional support resources',
            ];
        } else {
            $risk_level = 'High';
            $risk_color = 'red';
            $recommendations = [
                'Urgent review required for low-performing areas',
                'Develop comprehensive improvement plan',
                'Increase support staff and resources',
                'Schedule immediate stakeholder meetings',
                'Consider external quality assessment',
            ];
        }

        return [
            'score' => $score,
            'percentage' => round(($score / 5.0) * 100, 1),
            'risk_level' => $risk_level,
            'risk_color' => $risk_color,
            'recommendations' => $recommendations,
        ];
    }

    /**
     * Get distribution by grade level
     */
    private function getDistributionByGrade($responses)
    {
        return $responses->groupBy('grade_level')
            ->map(function ($group, $grade) {
                return [
                    'grade' => $grade,
                    'count' => $group->count(),
                    'percentage' => round(($group->count() / count($group)) * 100, 1),
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Get distribution by gender
     */
    private function getDistributionByGender($responses)
    {
        return $responses->groupBy('gender')
            ->map(function ($group, $gender) use ($responses) {
                return [
                    'gender' => $gender ?: 'Not specified',
                    'count' => $group->count(),
                    'percentage' => round(($group->count() / $responses->count()) * 100, 1),
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Get distribution by semester
     */
    private function getDistributionBySemester($responses)
    {
        return $responses->groupBy('semester')
            ->map(function ($group, $semester) use ($responses) {
                return [
                    'semester' => $semester,
                    'count' => $group->count(),
                    'percentage' => round(($group->count() / $responses->count()) * 100, 1),
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Get weekly response count for trend analysis
     */
    private function getWeeklyResponseCount($filters = [])
    {
        $query = $this->applyFilters(SurveyResponse::query(), $filters);
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            $weeklyData = $query
                ->select(DB::raw('YEARWEEK(created_at, 1) as year_week'), DB::raw('COUNT(*) as count'))
                ->groupBy('year_week')
                ->orderBy('year_week')
                ->get();

            return $weeklyData->map(function ($item) {
                $year = substr($item->year_week, 0, 4);
                $week = substr($item->year_week, 4, 2);

                return [
                    'week' => "Week {$week}, {$year}",
                    'count' => $item->count,
                ];
            })->toArray();
        } else {
            // SQLite and other databases - use strftime
            $weeklyData = $query
                ->select(
                    DB::raw("strftime('%Y-%W', created_at) as year_week"),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('year_week')
                ->orderBy('year_week')
                ->get();

            return $weeklyData->map(function ($item) {
                [$year, $week] = explode('-', $item->year_week);

                return [
                    'week' => "Week {$week}, {$year}",
                    'count' => $item->count,
                ];
            })->toArray();
        }
    }

    /**
     * Get monthly average satisfaction scores
     */
    private function getMonthlyAverages($filters = [])
    {
        $query = $this->applyFilters(SurveyResponse::query(), $filters);
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            $monthlyData = $query
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('AVG(overall_satisfaction) as avg_satisfaction'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        } else {
            // SQLite and other databases - use strftime
            $monthlyData = $query
                ->select(
                    DB::raw("strftime('%Y-%m', created_at) as month"),
                    DB::raw('AVG(overall_satisfaction) as avg_satisfaction'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        return $monthlyData->map(function ($item) {
            return [
                'month' => Carbon::createFromFormat('Y-m', $item->month)->format('M Y'),
                'average_satisfaction' => round($item->avg_satisfaction, 2),
                'response_count' => $item->count,
            ];
        })->toArray();
    }

    /**
     * Get time series data for satisfaction trends
     */
    public function getTimeSeriesData($metric = 'overall_satisfaction', $groupBy = 'week', $filters = [])
    {
        $query = $this->applyFilters(SurveyResponse::query(), $filters);
        $driver = DB::connection()->getDriverName();

        // Determine grouping format based on database driver
        if ($driver === 'mysql') {
            $dateFormat = match($groupBy) {
                'day' => '%Y-%m-%d',
                'week' => '%Y-%U',
                'month' => '%Y-%m',
                default => '%Y-%U',
            };

            $data = $query
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '{$dateFormat}') as period"),
                    DB::raw("AVG({$metric}) as average"),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        } else {
            // SQLite - use strftime
            $dateFormat = match($groupBy) {
                'day' => '%Y-%m-%d',
                'week' => '%Y-%W',
                'month' => '%Y-%m',
                default => '%Y-%W',
            };

            $data = $query
                ->select(
                    DB::raw("strftime('{$dateFormat}', created_at) as period"),
                    DB::raw("AVG({$metric}) as average"),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        }

        return [
            'labels' => $data->pluck('period')->map(function ($period) use ($groupBy) {
                return $this->formatPeriodLabel($period, $groupBy);
            })->toArray(),
            'data' => $data->pluck('average')->map(fn($val) => round($val, 2))->toArray(),
            'counts' => $data->pluck('count')->toArray(),
        ];
    }

    /**
     * Format period label for display
     */
    private function formatPeriodLabel($period, $groupBy)
    {
        return match($groupBy) {
            'day' => Carbon::createFromFormat('Y-m-d', $period)->format('M d'),
            'week' => 'Week ' . substr($period, -2) . ' ' . substr($period, 0, 4),
            'month' => Carbon::createFromFormat('Y-m', $period)->format('M Y'),
            default => $period,
        };
    }

    /**
     * Apply filters to query
     */
    private function applyFilters($query, $filters)
    {
        if (!empty($filters['track'])) {
            $query->where('track', $filters['track']);
        }

        if (!empty($filters['grade_level'])) {
            $query->where('grade_level', $filters['grade_level']);
        }

        if (!empty($filters['semester'])) {
            $query->where('semester', $filters['semester']);
        }

        if (!empty($filters['academic_year'])) {
            $query->where('academic_year', $filters['academic_year']);
        }

        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query;
    }

    /**
     * Get date range from responses
     */
    private function getDateRange($responses)
    {
        if ($responses->isEmpty()) {
            return null;
        }

        return [
            'from' => $responses->min('created_at'),
            'to' => $responses->max('created_at'),
        ];
    }

    /**
     * Get applied filters summary
     */
    private function getAppliedFilters($filters)
    {
        $applied = [];

        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                $applied[$key] = $value;
            }
        }

        return $applied;
    }
}
