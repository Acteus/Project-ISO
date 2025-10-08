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
}
