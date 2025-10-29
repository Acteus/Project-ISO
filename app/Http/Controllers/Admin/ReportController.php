<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WeeklyProgressReport;
use App\Mail\MonthlyComplianceReport;
use App\Models\Admin;
use App\Models\WeeklyMetric;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    public function index()
    {
        $admins = Admin::all();
        $qrCodes = QrCode::orderBy('created_at', 'desc')->paginate(6);
        return view('admin.reports', compact('admins', 'qrCodes'));
    }

    public function sendWeeklyReport(Request $request)
    {
        $request->validate([
            'recipient_email' => 'required|email',
            'month' => 'required|string',
            'week_number' => 'required|integer|min:1|max:5',
        ]);

        try {
            // Get weekly data for the specified period
            $weeklyData = $this->getWeeklyReportData($request->month, $request->week_number);

            if (!$weeklyData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data available for the selected week period.'
                ], 404);
            }

            // Send the email using WeeklyMetric objects
            $weeklyMetric = $weeklyData['metric'];
            $previousMetric = $weeklyData['previous_metric'];
            Mail::to($request->recipient_email)->send(new WeeklyProgressReport($weeklyMetric, $previousMetric));

            Log::info('Weekly progress report sent', [
                'recipient' => $request->recipient_email,
                'month' => $request->month,
                'week_number' => $request->week_number
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Weekly progress report sent successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send weekly report', [
                'error' => $e->getMessage(),
                'recipient' => $request->recipient_email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send report. Please try again.'
            ], 500);
        }
    }

    public function sendMonthlyReport(Request $request)
    {
        $request->validate([
            'recipient_email' => 'required|email',
            'month' => 'required|string',
        ]);

        try {
            // Get monthly data for the specified period
            $monthlyData = $this->getMonthlyReportData($request->month);

            if (!$monthlyData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data available for the selected month.'
                ], 404);
            }

            // Send the email
            Mail::to($request->recipient_email)->send(new MonthlyComplianceReport($monthlyData));

            Log::info('Monthly compliance report sent', [
                'recipient' => $request->recipient_email,
                'month' => $request->month
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Monthly compliance report sent successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send monthly report', [
                'error' => $e->getMessage(),
                'recipient' => $request->recipient_email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send report. Please try again.'
            ], 500);
        }
    }

    public function previewWeeklyReport(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'week_number' => 'required|integer|min:1|max:5',
        ]);

        $weeklyData = $this->getWeeklyReportData($request->month, $request->week_number);

        Log::info('Weekly preview data check', [
            'month' => $request->month,
            'week_number' => $request->week_number,
            'data_exists' => !empty($weeklyData),
            'data_type' => gettype($weeklyData),
            'data_keys' => is_array($weeklyData) ? array_keys($weeklyData) : null
        ]);

        if (!$weeklyData) {
            return response()->json([
                'success' => false,
                'message' => 'No data available for the selected week period.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $weeklyData
        ]);
    }

    public function previewMonthlyReport(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
        ]);

        $monthlyData = $this->getMonthlyReportData($request->month);

        Log::info('Monthly preview data check', [
            'month' => $request->month,
            'data_exists' => !empty($monthlyData),
            'data_type' => gettype($monthlyData),
            'data_keys' => is_array($monthlyData) ? array_keys($monthlyData) : null
        ]);

        if (!$monthlyData) {
            return response()->json([
                'success' => false,
                'message' => 'No data available for the selected month.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $monthlyData
        ]);
    }

    private function getWeeklyReportData($month, $weekNumber)
    {
        // Parse month and year
        $monthMap = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
            'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
            'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];

        $parts = explode(' ', $month);
        $monthName = $parts[0];
        $year = isset($parts[1]) ? intval($parts[1]) : 2025;
        $monthNum = $monthMap[$monthName] ?? 1;

        // Get the start and end of the selected month
        $monthStart = new \DateTime("$year-" . str_pad($monthNum, 2, '0', STR_PAD_LEFT) . "-01");
        $monthEnd = clone $monthStart;
        $monthEnd->modify('last day of this month')->setTime(23, 59, 59);

        // Get all weekly metrics that overlap with this month
        $weeklyMetrics = WeeklyMetric::where('year', $year)
            ->where(function($query) use ($monthStart, $monthEnd) {
                $query->whereBetween('week_start_date', [$monthStart->format('Y-m-d'), $monthEnd->format('Y-m-d')])
                      ->orWhereBetween('week_end_date', [$monthStart->format('Y-m-d'), $monthEnd->format('Y-m-d')])
                      ->orWhere(function($q) use ($monthStart, $monthEnd) {
                          $q->where('week_start_date', '<=', $monthStart->format('Y-m-d'))
                            ->where('week_end_date', '>=', $monthEnd->format('Y-m-d'));
                      });
            })
            ->orderBy('week_start_date')
            ->get();

        Log::info('Weekly metrics found for month', [
            'month' => $month,
            'year' => $year,
            'month_num' => $monthNum,
            'month_start' => $monthStart->format('Y-m-d'),
            'month_end' => $monthEnd->format('Y-m-d'),
            'metrics_found' => $weeklyMetrics->count(),
            'metric_ids' => $weeklyMetrics->pluck('id')->toArray(),
            'week_numbers' => $weeklyMetrics->pluck('week_number')->toArray()
        ]);

        // Return null if no data exists
        if ($weeklyMetrics->isEmpty()) {
            Log::warning('No weekly metrics found', [
                'month' => $month,
                'week_number' => $weekNumber
            ]);
            return null;
        }

        // Get the specific week based on the week number (1-5) within the month
        if ($weekNumber > $weeklyMetrics->count()) {
            Log::warning('Week number out of range', [
                'requested_week' => $weekNumber,
                'available_weeks' => $weeklyMetrics->count()
            ]);
            return null;
        }

        $weeklyMetric = $weeklyMetrics[$weekNumber - 1]; // Array is 0-indexed, weeks are 1-indexed

        // Get previous week's data for comparison (could be from previous month)
        $previousMetric = WeeklyMetric::where('year', $year)
            ->where('week_number', $weeklyMetric->week_number - 1)
            ->first();

        Log::info('Weekly data retrieved', [
            'requested_week' => $weekNumber,
            'iso_week_number' => $weeklyMetric->week_number,
            'week_start' => $weeklyMetric->week_start_date,
            'week_end' => $weeklyMetric->week_end_date,
            'metric_id' => $weeklyMetric->id,
            'previous_metric_id' => $previousMetric ? $previousMetric->id : null
        ]);

        return [
            'metric' => $weeklyMetric,
            'previous_metric' => $previousMetric,
            'week_start' => $weeklyMetric->week_start_date,
            'week_end' => $weeklyMetric->week_end_date,
            'month' => $month,
            'week_number' => $weekNumber,
            'iso_week_number' => $weeklyMetric->week_number,
            'current' => [
                'overall_satisfaction' => round($weeklyMetric->overall_satisfaction, 2),
                'compliance_percentage' => round($weeklyMetric->compliance_percentage, 2),
                'new_responses' => $weeklyMetric->new_responses,
                'total_responses' => $weeklyMetric->total_responses,
                'risk_level' => $weeklyMetric->risk_level
            ],
            'previous' => $previousMetric ? [
                'overall_satisfaction' => round($previousMetric->overall_satisfaction, 2),
                'compliance_percentage' => round($previousMetric->compliance_percentage, 2),
                'new_responses' => $previousMetric->new_responses,
                'total_responses' => $previousMetric->total_responses,
                'risk_level' => $previousMetric->risk_level
            ] : null,
            'comparison' => $this->generateComparison($weeklyMetric, $previousMetric),
            'insights' => $weeklyMetric->key_insights ?? []
        ];
    }

    private function getMonthlyReportData($month)
    {
        // Parse month and year
        $monthMap = [
            'January' => 1, 'February' => 2, 'March' => 3, 'April' => 4,
            'May' => 5, 'June' => 6, 'July' => 7, 'August' => 8,
            'September' => 9, 'October' => 10, 'November' => 11, 'December' => 12
        ];

        $year = 2025; // Default year, you can make this dynamic
        $monthNum = $monthMap[explode(' ', $month)[0]] ?? 1;

        // Query weekly metrics for the month
        $startOfMonth = \DateTime::createFromFormat('Y-m-d', "$year-" . str_pad($monthNum, 2, '0', STR_PAD_LEFT) . '-01');
        $endOfMonth = clone $startOfMonth;
        $endOfMonth->modify('last day of this month');

        $weeklyMetrics = WeeklyMetric::where('year', $year)
            ->where('week_start_date', '>=', $startOfMonth->format('Y-m-d'))
            ->where('week_start_date', '<=', $endOfMonth->format('Y-m-d'))
            ->orderBy('week_number')
            ->get();

        Log::info('Monthly data query result', [
            'month' => $month,
            'year' => $year,
            'month_num' => $monthNum,
            'metrics_count' => $weeklyMetrics->count(),
            'metric_ids' => $weeklyMetrics->pluck('id')->toArray()
        ]);

        // Return null if no data exists
        if ($weeklyMetrics->isEmpty()) {
            return null;
        }

        // Calculate monthly averages
        $monthlyAverages = [
            'overall_satisfaction' => $weeklyMetrics->avg('overall_satisfaction'),
            'compliance_score' => $weeklyMetrics->avg('compliance_score'),
            'safety_index' => $weeklyMetrics->avg('safety_index'),
            'total_responses' => $weeklyMetrics->sum('total_responses')
        ];

        // Check targets (you may want to define these in a config or database)
        $targetsAchieved = [
            'satisfaction' => $monthlyAverages['overall_satisfaction'] >= 4.0,
            'compliance' => $monthlyAverages['compliance_score'] >= 80,
            'responses' => $monthlyAverages['total_responses'] >= 600
        ];

        // Calculate trends (comparing to previous month)
        $previousMonth = $monthNum - 1;
        $previousYear = $year;
        if ($previousMonth < 1) {
            $previousMonth = 12;
            $previousYear--;
        }

        // Use date ranges instead of MONTH() function for SQLite compatibility
        $previousMonthStart = new \DateTime("$previousYear-" . str_pad($previousMonth, 2, '0', STR_PAD_LEFT) . "-01");
        $previousMonthEnd = clone $previousMonthStart;
        $previousMonthEnd->modify('last day of this month')->setTime(23, 59, 59);

        $previousMonthMetrics = WeeklyMetric::where('year', $previousYear)
            ->where(function($query) use ($previousMonthStart, $previousMonthEnd) {
                $query->whereBetween('week_start_date', [$previousMonthStart->format('Y-m-d'), $previousMonthEnd->format('Y-m-d')])
                      ->orWhereBetween('week_end_date', [$previousMonthStart->format('Y-m-d'), $previousMonthEnd->format('Y-m-d')])
                      ->orWhere(function($q) use ($previousMonthStart, $previousMonthEnd) {
                          $q->where('week_start_date', '<=', $previousMonthStart->format('Y-m-d'))
                            ->where('week_end_date', '>=', $previousMonthEnd->format('Y-m-d'));
                      });
            })
            ->get();

        $trends = [];
        if ($previousMonthMetrics->isNotEmpty()) {
            $trends = [
                'satisfaction_change' => round($monthlyAverages['overall_satisfaction'] - $previousMonthMetrics->avg('overall_satisfaction'), 2),
                'compliance_change' => round($monthlyAverages['compliance_score'] - $previousMonthMetrics->avg('compliance_score'), 2),
                'response_total' => $monthlyAverages['total_responses']
            ];
        }

        return [
            'month' => $month,
            'monthly_averages' => $monthlyAverages,
            'targets_achieved' => $targetsAchieved,
            'trends' => $trends,
            'weekly_data' => $weeklyMetrics->map(function ($metric) {
                $weekStart = new \DateTime($metric->week_start_date);
                $weekEnd = new \DateTime($metric->week_end_date);
                return (object)[
                    'date_range_label' => $weekStart->format('M j') . ' - ' . $weekEnd->format('M j, Y'),
                    'overall_satisfaction' => round($metric->overall_satisfaction, 2),
                    'compliance_score' => round($metric->compliance_score, 2),
                    'safety_index' => round($metric->safety_index, 2),
                    'new_responses' => $metric->new_responses,
                    'risk_level' => $metric->risk_level
                ];
            })
        ];
    }

    private function generateComparison($currentMetric, $previousMetric)
    {
        if (!$previousMetric) {
            return null;
        }

        return [
            'overall_satisfaction' => [
                'change' => round($currentMetric->overall_satisfaction - $previousMetric->overall_satisfaction, 2),
                'trend' => $currentMetric->overall_satisfaction > $previousMetric->overall_satisfaction ? 'up' :
                          ($currentMetric->overall_satisfaction < $previousMetric->overall_satisfaction ? 'down' : 'stable')
            ],
            'compliance_score' => [
                'change' => round($currentMetric->compliance_score - $previousMetric->compliance_score, 2),
                'trend' => $currentMetric->compliance_score > $previousMetric->compliance_score ? 'up' :
                          ($currentMetric->compliance_score < $previousMetric->compliance_score ? 'down' : 'stable')
            ],
            'new_responses' => [
                'change' => $currentMetric->new_responses - $previousMetric->new_responses,
                'trend' => $currentMetric->new_responses > $previousMetric->new_responses ? 'up' :
                          ($currentMetric->new_responses < $previousMetric->new_responses ? 'down' : 'stable')
            ]
        ];
    }
}

