<?php

namespace App\Http\Controllers;

use App\Services\VisualizationService;
use Illuminate\Http\Request;

class VisualizationController extends Controller
{
    protected $visualizationService;

    public function __construct(VisualizationService $visualizationService)
    {
        $this->visualizationService = $visualizationService;
    }

    public function getBarChartData(Request $request)
    {
        $track = $request->query('track');
        $gradeLevel = $request->query('grade_level');
        $academicYear = $request->query('academic_year');
        $semester = $request->query('semester');

        $data = $this->visualizationService->generateBarChartData($track, $gradeLevel, $academicYear, $semester);

        return response()->json([
            'message' => 'ISO 21001 Bar chart data generated successfully',
            'data' => $data
        ]);
    }

    public function getPieChartData(Request $request)
    {
        $track = $request->query('track');
        $gradeLevel = $request->query('grade_level');
        $academicYear = $request->query('academic_year');
        $semester = $request->query('semester');

        $data = $this->visualizationService->generatePieChartData($track, $gradeLevel, $academicYear, $semester);

        return response()->json([
            'message' => 'ISO 21001 Pie chart data generated successfully',
            'data' => $data
        ]);
    }

    public function getRadarChartData(Request $request)
    {
        $track = $request->query('track');
        $gradeLevel = $request->query('grade_level');
        $academicYear = $request->query('academic_year');
        $semester = $request->query('semester');

        $data = $this->visualizationService->generateRadarChartData($track, $gradeLevel, $academicYear, $semester);

        return response()->json([
            'message' => 'ISO 21001 Radar chart data generated successfully',
            'data' => $data
        ]);
    }

    public function getWordCloudData(Request $request)
    {
        $track = $request->query('track');
        $gradeLevel = $request->query('grade_level');
        $academicYear = $request->query('academic_year');
        $semester = $request->query('semester');
        $minFrequency = $request->query('min_frequency', 2);

        $data = $this->visualizationService->generateWordCloudData($track, $gradeLevel, $academicYear, $semester, $minFrequency);

        return response()->json([
            'message' => 'ISO 21001 Word cloud data generated successfully',
            'data' => $data
        ]);
    }

    public function getTrackComparisonData(Request $request)
    {
        $data = $this->visualizationService->generateTrackComparisonChart();

        return response()->json([
            'message' => 'Track comparison data generated successfully',
            'data' => $data
        ]);
    }

    public function getGradeLevelTrendData(Request $request)
    {
        $academicYear = $request->query('academic_year');
        $semester = $request->query('semester');

        $data = $this->visualizationService->generateGradeLevelTrendChart($academicYear, $semester);

        return response()->json([
            'message' => 'Grade level trend data generated successfully',
            'data' => $data
        ]);
    }

    public function getDashboardData(Request $request)
    {
        $track = $request->query('track');
        $gradeLevel = $request->query('grade_level');
        $academicYear = $request->query('academic_year');
        $semester = $request->query('semester');

        $dashboardData = [
            'bar_chart' => $this->visualizationService->generateBarChartData($track, $gradeLevel, $academicYear, $semester),
            'pie_chart' => $this->visualizationService->generatePieChartData($track, $gradeLevel, $academicYear, $semester),
            'radar_chart' => $this->visualizationService->generateRadarChartData($track, $gradeLevel, $academicYear, $semester),
            'word_cloud' => $this->visualizationService->generateWordCloudData($track, $gradeLevel, $academicYear, $semester),
            'track_comparison' => $this->visualizationService->generateTrackComparisonChart(),
            'grade_trend' => $this->visualizationService->generateGradeLevelTrendChart($academicYear, $semester),
        ];

        return response()->json([
            'message' => 'ISO 21001 Dashboard data generated successfully',
            'data' => $dashboardData
        ]);
    }

    /**
     * Get time-series trend data for analytics
     */
    public function getTimeSeriesData(Request $request)
    {
        $metric = $request->query('metric', 'overall_satisfaction');
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');
        $groupBy = $request->query('group_by', 'week');

        $data = $this->visualizationService->generateTimeSeriesData($metric, $dateFrom, $dateTo, $groupBy);

        return response()->json([
            'message' => 'Time-series data generated successfully',
            'data' => $data
        ]);
    }

    /**
     * Get heat map data for track/grade performance
     */
    public function getHeatMapData(Request $request)
    {
        $metric = $request->query('metric', 'overall_satisfaction');

        $data = $this->visualizationService->generateHeatMapData($metric);

        return response()->json([
            'message' => 'Heat map data generated successfully',
            'data' => $data
        ]);
    }

    /**
     * Get compliance risk meter data
     */
    public function getComplianceRiskData(Request $request)
    {
        $data = $this->visualizationService->generateComplianceRiskData();

        return response()->json([
            'message' => 'Compliance risk data generated successfully',
            'data' => $data
        ]);
    }

    /**
     * Get comparative period analysis
     */
    public function getComparativeAnalysis(Request $request)
    {
        $currentDateFrom = $request->query('current_from');
        $currentDateTo = $request->query('current_to');
        $previousDateFrom = $request->query('previous_from');
        $previousDateTo = $request->query('previous_to');

        $data = $this->visualizationService->generateComparativeAnalysis(
            $currentDateFrom,
            $currentDateTo,
            $previousDateFrom,
            $previousDateTo
        );

        return response()->json([
            'message' => 'Comparative analysis generated successfully',
            'data' => $data
        ]);
    }

    /**
     * Get response rate analytics
     */
    public function getResponseRateAnalytics(Request $request)
    {
        $data = $this->visualizationService->generateResponseRateAnalytics();

        return response()->json([
            'message' => 'Response rate analytics generated successfully',
            'data' => $data
        ]);
    }
}
