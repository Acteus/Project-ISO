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
        $program = $request->query('program');
        $yearLevel = $request->query('year_level');

        $data = $this->visualizationService->generateBarChartData($program, $yearLevel);

        return response()->json([
            'message' => 'Bar chart data generated successfully',
            'data' => $data
        ]);
    }

    public function getPieChartData(Request $request)
    {
        $program = $request->query('program');
        $yearLevel = $request->query('year_level');

        $data = $this->visualizationService->generatePieChartData($program, $yearLevel);

        return response()->json([
            'message' => 'Pie chart data generated successfully',
            'data' => $data
        ]);
    }

    public function getRadarChartData(Request $request)
    {
        $program = $request->query('program');
        $yearLevel = $request->query('year_level');

        $data = $this->visualizationService->generateRadarChartData($program, $yearLevel);

        return response()->json([
            'message' => 'Radar chart data generated successfully',
            'data' => $data
        ]);
    }

    public function getWordCloudData(Request $request)
    {
        $minFrequency = $request->query('min_frequency', 2);

        $data = $this->visualizationService->generateWordCloudData($minFrequency);

        return response()->json([
            'message' => 'Word cloud data generated successfully',
            'data' => $data
        ]);
    }

    public function getProgramComparisonData(Request $request)
    {
        $data = $this->visualizationService->generateProgramComparisonChart();

        return response()->json([
            'message' => 'Program comparison data generated successfully',
            'data' => $data
        ]);
    }

    public function getYearLevelTrendData(Request $request)
    {
        $data = $this->visualizationService->generateYearLevelTrendChart();

        return response()->json([
            'message' => 'Year level trend data generated successfully',
            'data' => $data
        ]);
    }

    public function getDashboardData(Request $request)
    {
        $program = $request->query('program');
        $yearLevel = $request->query('year_level');

        $dashboardData = [
            'bar_chart' => $this->visualizationService->generateBarChartData($program, $yearLevel),
            'pie_chart' => $this->visualizationService->generatePieChartData($program, $yearLevel),
            'radar_chart' => $this->visualizationService->generateRadarChartData($program, $yearLevel),
            'word_cloud' => $this->visualizationService->generateWordCloudData(),
            'program_comparison' => $this->visualizationService->generateProgramComparisonChart(),
            'year_trend' => $this->visualizationService->generateYearLevelTrendChart(),
        ];

        return response()->json([
            'message' => 'Dashboard data generated successfully',
            'data' => $dashboardData
        ]);
    }
}
