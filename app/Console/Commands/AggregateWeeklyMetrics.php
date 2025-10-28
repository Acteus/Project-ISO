<?php

namespace App\Console\Commands;

use App\Models\SurveyResponse;
use App\Models\WeeklyMetric;
use App\Services\VisualizationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AggregateWeeklyMetrics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:aggregate-weekly-metrics {--week= : Specific week to aggregate (YYYY-WW format)} {--force : Force re-aggregation of existing weeks}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregate weekly metrics for ISO 21001 compliance tracking and progress analysis';

    protected $visualizationService;

    public function __construct(VisualizationService $visualizationService)
    {
        parent::__construct();
        $this->visualizationService = $visualizationService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting weekly metrics aggregation...');

        $specificWeek = $this->option('week');
        $force = $this->option('force');

        if ($specificWeek) {
            // Aggregate specific week
            $this->aggregateSpecificWeek($specificWeek, $force);
        } else {
            // Aggregate current week and any missing recent weeks
            $this->aggregateRecentWeeks($force);
        }

        $this->info('Weekly metrics aggregation completed.');
    }

    /**
     * Aggregate metrics for a specific week
     */
    private function aggregateSpecificWeek($weekString, $force = false)
    {
        [$year, $week] = explode('-W', $weekString);
        $year = (int) $year;
        $week = (int) $week;

        // Calculate week date range
        $weekStart = Carbon::createFromFormat('Y W', $year . ' ' . $week)->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();

        $this->info("Aggregating metrics for Week {$week}, {$year} ({$weekStart->format('M j')} - {$weekEnd->format('M j, Y')})");

        // Check if already exists
        $existing = WeeklyMetric::where('year', $year)->where('week_number', $week)->first();

        if ($existing && !$force) {
            $this->warn("Metrics for Week {$week}, {$year} already exist. Use --force to re-aggregate.");
            return;
        }

        $this->aggregateWeekMetrics($weekStart, $weekEnd, $year, $week, $existing);
    }

    /**
     * Aggregate metrics for recent weeks
     */
    private function aggregateRecentWeeks($force = false)
    {
        $now = Carbon::now();
        $currentWeekStart = $now->copy()->startOfWeek();

        // Aggregate last 12 weeks
        for ($i = 0; $i < 12; $i++) {
            $weekStart = $currentWeekStart->copy()->subWeeks($i);
            $weekEnd = $weekStart->copy()->endOfWeek();
            $year = (int) $weekStart->format('Y');
            $week = (int) $weekStart->format('W');

            $this->info("Checking Week {$week}, {$year}...");

            $existing = WeeklyMetric::where('year', $year)->where('week_number', $week)->first();

            if (!$existing || $force) {
                $this->aggregateWeekMetrics($weekStart, $weekEnd, $year, $week, $existing);
            } else {
                $this->info("Week {$week}, {$year} already aggregated.");
            }
        }
    }

    /**
     * Aggregate metrics for a specific week period
     */
    private function aggregateWeekMetrics(Carbon $weekStart, Carbon $weekEnd, $year, $week, $existing = null)
    {
        // Get responses for this week
        $weekResponses = SurveyResponse::whereBetween('created_at', [
            $weekStart->format('Y-m-d 00:00:00'),
            $weekEnd->format('Y-m-d 23:59:59')
        ])->get();

        // Get cumulative responses up to end of week
        $cumulativeResponses = SurveyResponse::where('created_at', '<=', $weekEnd->format('Y-m-d 23:59:59'))->get();

        // Calculate metrics
        $metrics = $this->calculateWeeklyMetrics($weekResponses, $cumulativeResponses, $weekStart, $weekEnd);

        // Save or update
        if ($existing) {
            $existing->update($metrics);
            $this->info("Updated metrics for Week {$week}, {$year}");
        } else {
            WeeklyMetric::create($metrics);
            $this->info("Created metrics for Week {$week}, {$year}");
        }
    }

    /**
     * Calculate all metrics for a week
     */
    private function calculateWeeklyMetrics($weekResponses, $cumulativeResponses, Carbon $weekStart, Carbon $weekEnd)
    {
        $metrics = [
            'week_start_date' => $weekStart->format('Y-m-d'),
            'week_end_date' => $weekEnd->format('Y-m-d'),
            'year' => (int) $weekStart->format('Y'),
            'week_number' => (int) $weekStart->format('W'),
            'total_responses' => $cumulativeResponses->count(),
            'new_responses' => $weekResponses->count(),
        ];

        if ($cumulativeResponses->isEmpty()) {
            // No data available
            return array_merge($metrics, [
                'learner_needs_index' => null,
                'satisfaction_score' => null,
                'success_index' => null,
                'safety_index' => null,
                'wellbeing_index' => null,
                'overall_satisfaction' => null,
                'compliance_score' => null,
                'compliance_percentage' => null,
                'risk_level' => null,
                'responses_by_track' => [],
                'responses_by_grade' => [],
                'responses_by_gender' => [],
                'satisfaction_trend' => null,
                'compliance_trend' => null,
                'response_trend' => null,
                'satisfaction_target_met' => false,
                'compliance_target_met' => false,
                'response_target_met' => false,
                'key_insights' => [],
                'recommendations' => [],
            ]);
        }

        // Calculate ISO 21001 indices
        $metrics['learner_needs_index'] = round((
            $cumulativeResponses->avg('curriculum_relevance_rating') +
            $cumulativeResponses->avg('learning_pace_appropriateness') +
            $cumulativeResponses->avg('individual_support_availability') +
            $cumulativeResponses->avg('learning_style_accommodation')
        ) / 4, 2);

        $metrics['satisfaction_score'] = round((
            $cumulativeResponses->avg('teaching_quality_rating') +
            $cumulativeResponses->avg('learning_environment_rating') +
            $cumulativeResponses->avg('peer_interaction_satisfaction') +
            $cumulativeResponses->avg('extracurricular_satisfaction')
        ) / 4, 2);

        $metrics['success_index'] = round((
            $cumulativeResponses->avg('academic_progress_rating') +
            $cumulativeResponses->avg('skill_development_rating') +
            $cumulativeResponses->avg('critical_thinking_improvement') +
            $cumulativeResponses->avg('problem_solving_confidence')
        ) / 4, 2);

        $metrics['safety_index'] = round((
            $cumulativeResponses->avg('physical_safety_rating') +
            $cumulativeResponses->avg('psychological_safety_rating') +
            $cumulativeResponses->avg('bullying_prevention_effectiveness') +
            $cumulativeResponses->avg('emergency_preparedness_rating')
        ) / 4, 2);

        $metrics['wellbeing_index'] = round((
            $cumulativeResponses->avg('mental_health_support_rating') +
            $cumulativeResponses->avg('stress_management_support') +
            $cumulativeResponses->avg('physical_health_support') +
            $cumulativeResponses->avg('overall_wellbeing_rating')
        ) / 4, 2);

        $metrics['overall_satisfaction'] = round($cumulativeResponses->avg('overall_satisfaction'), 2);

        // Calculate compliance
        $complianceScore = (
            $metrics['learner_needs_index'] * 0.15 +
            $metrics['satisfaction_score'] * 0.25 +
            $metrics['success_index'] * 0.20 +
            $metrics['safety_index'] * 0.20 +
            $metrics['wellbeing_index'] * 0.15 +
            $metrics['overall_satisfaction'] * 0.05
        );

        $metrics['compliance_score'] = round($complianceScore, 2);
        $metrics['compliance_percentage'] = round(($complianceScore / 5) * 100, 2);

        // Determine risk level
        if ($complianceScore >= 4.2) {
            $metrics['risk_level'] = 'Low';
        } elseif ($complianceScore >= 3.5) {
            $metrics['risk_level'] = 'Medium';
        } else {
            $metrics['risk_level'] = 'High';
        }

        // Demographic breakdowns
        $metrics['responses_by_track'] = $cumulativeResponses->groupBy('track')->map->count()->toArray();
        $metrics['responses_by_grade'] = $cumulativeResponses->groupBy('grade_level')->map->count()->toArray();
        $metrics['responses_by_gender'] = $cumulativeResponses->whereNotNull('gender')->groupBy('gender')->map->count()->toArray();

        // Calculate trends (compared to previous week)
        $previousWeek = WeeklyMetric::where('year', $metrics['year'])
            ->where('week_number', $metrics['week_number'] - 1)
            ->first();

        if ($previousWeek) {
            if ($previousWeek->overall_satisfaction > 0) {
                $metrics['satisfaction_trend'] = round(
                    (($metrics['overall_satisfaction'] - $previousWeek->overall_satisfaction) / $previousWeek->overall_satisfaction) * 100,
                    2
                );
            }

            if ($previousWeek->compliance_score > 0) {
                $metrics['compliance_trend'] = round(
                    (($metrics['compliance_score'] - $previousWeek->compliance_score) / $previousWeek->compliance_score) * 100,
                    2
                );
            }

            if ($previousWeek->total_responses > 0) {
                $metrics['response_trend'] = round(
                    (($metrics['total_responses'] - $previousWeek->total_responses) / $previousWeek->total_responses) * 100,
                    2
                );
            }
        }

        // Check targets
        $metrics['satisfaction_target_met'] = $metrics['overall_satisfaction'] >= 4.0;
        $metrics['compliance_target_met'] = $metrics['compliance_percentage'] >= 80.0;
        $metrics['response_target_met'] = $metrics['new_responses'] >= 50;

        // Generate insights and recommendations
        $metrics['key_insights'] = $this->generateKeyInsights($metrics);
        $metrics['recommendations'] = $this->generateRecommendations($metrics);

        return $metrics;
    }

    /**
     * Generate key insights based on metrics
     */
    private function generateKeyInsights($metrics)
    {
        $insights = [];

        if ($metrics['overall_satisfaction'] >= 4.2) {
            $insights[] = 'Excellent overall satisfaction this week';
        } elseif ($metrics['overall_satisfaction'] >= 3.5) {
            $insights[] = 'Good satisfaction levels maintained';
        } else {
            $insights[] = 'Satisfaction levels need improvement';
        }

        if ($metrics['new_responses'] > 100) {
            $insights[] = 'High response volume indicates strong engagement';
        } elseif ($metrics['new_responses'] < 20) {
            $insights[] = 'Low response volume - consider outreach efforts';
        }

        if (isset($metrics['satisfaction_trend']) && $metrics['satisfaction_trend'] > 5) {
            $insights[] = 'Significant improvement in satisfaction trends';
        } elseif (isset($metrics['satisfaction_trend']) && $metrics['satisfaction_trend'] < -5) {
            $insights[] = 'Declining satisfaction trends require attention';
        }

        return $insights;
    }

    /**
     * Generate recommendations based on metrics
     */
    private function generateRecommendations($metrics)
    {
        $recommendations = [];

        if ($metrics['safety_index'] < 3.5) {
            $recommendations[] = 'URGENT: Enhance safety protocols and emergency preparedness';
        }

        if ($metrics['wellbeing_index'] < 3.5) {
            $recommendations[] = 'PRIORITY: Strengthen wellbeing support programs';
        }

        if ($metrics['satisfaction_score'] < 3.5) {
            $recommendations[] = 'IMMEDIATE: Address learner satisfaction concerns';
        }

        if ($metrics['new_responses'] < 30) {
            $recommendations[] = 'Increase survey participation through targeted communications';
        }

        if (empty($recommendations)) {
            $recommendations[] = 'Continue monitoring and maintaining high standards';
        }

        return $recommendations;
    }
}
