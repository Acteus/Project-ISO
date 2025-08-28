<?php

namespace App\Http\Controllers;

use App\Exports\SurveyResponsesExport;
use App\Models\SurveyResponse;
use App\Services\AIService;
use App\Services\VisualizationService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ExportController extends Controller
{
    protected $aiService;
    protected $visualizationService;

    public function __construct(AIService $aiService, VisualizationService $visualizationService)
    {
        $this->aiService = $aiService;
        $this->visualizationService = $visualizationService;
    }

    public function exportExcel(Request $request)
    {
        $program = $request->query('program');
        $yearLevel = $request->query('year_level');

        $filename = 'survey_responses_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        // Log the export activity
        $request->user()->auditLogs()->create([
            'action' => 'export_excel',
            'description' => "Exported survey responses to Excel (Program: " . ($program ?: 'All') . ", Year: " . ($yearLevel ?: 'All') . ")",
            'ip_address' => $request->ip(),
        ]);

        return Excel::download(new SurveyResponsesExport($program, $yearLevel), $filename);
    }

    public function exportCsv(Request $request)
    {
        $program = $request->query('program');
        $yearLevel = $request->query('year_level');

        $filename = 'survey_responses_' . now()->format('Y-m-d_H-i-s') . '.csv';

        // Log the export activity
        $request->user()->auditLogs()->create([
            'action' => 'export_csv',
            'description' => "Exported survey responses to CSV (Program: " . ($program ?: 'All') . ", Year: " . ($yearLevel ?: 'All') . ")",
            'ip_address' => $request->ip(),
        ]);

        return Excel::download(new SurveyResponsesExport($program, $yearLevel), $filename, \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPdf(Request $request)
    {
        $program = $request->query('program');
        $yearLevel = $request->query('year_level');

        $query = SurveyResponse::query();

        if ($program) {
            $query->where('program', $program);
        }

        if ($yearLevel) {
            $query->where('year_level', $yearLevel);
        }

        $responses = $query->get();
        $analytics = $this->getAnalyticsData($responses, $program, $yearLevel);

        // Generate HTML content for PDF
        $html = $this->generatePdfHtml($responses, $analytics, $program, $yearLevel);

        // Configure Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'survey_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        // Log the export activity
        $request->user()->auditLogs()->create([
            'action' => 'export_pdf',
            'description' => "Exported survey report to PDF (Program: " . ($program ?: 'All') . ", Year: " . ($yearLevel ?: 'All') . ")",
            'ip_address' => $request->ip(),
        ]);

        return $dompdf->stream($filename);
    }

    public function exportAnalyticsReport(Request $request)
    {
        $program = $request->query('program');
        $yearLevel = $request->query('year_level');
        $format = $request->query('format', 'pdf'); // pdf or excel

        $query = SurveyResponse::query();

        if ($program) {
            $query->where('program', $program);
        }

        if ($yearLevel) {
            $query->where('year_level', $yearLevel);
        }

        $responses = $query->get();
        $analytics = $this->getAnalyticsData($responses, $program, $yearLevel);

        // Only support PDF format for analytics reports
        if ($format === 'excel') {
            return response()->json([
                'message' => 'Excel format not available for analytics reports. Use PDF format.',
                'available_formats' => ['pdf']
            ], 400);
        }
            // Generate PDF analytics report
            $html = $this->generateAnalyticsPdfHtml($analytics, $program, $yearLevel);

            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $filename = 'analytics_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';

            $request->user()->auditLogs()->create([
                'action' => 'export_analytics_pdf',
                'description' => "Exported analytics report to PDF (Program: " . ($program ?: 'All') . ", Year: " . ($yearLevel ?: 'All') . ")",
                'ip_address' => $request->ip(),
            ]);

            return $dompdf->stream($filename);
    }

    private function getAnalyticsData($responses, $program, $yearLevel)
    {
        return [
            'total_responses' => $responses->count(),
            'average_ratings' => [
                'course_content' => round($responses->avg('course_content_rating'), 2),
                'facilities' => round($responses->avg('facilities_rating'), 2),
                'support_services' => round($responses->avg('support_services_rating'), 2),
                'overall_satisfaction' => round($responses->avg('overall_satisfaction'), 2),
            ],
            'distribution' => [
                'program' => $responses->groupBy('program')->map->count(),
                'year_level' => $responses->groupBy('year_level')->map->count(),
            ],
            'consent_rate' => round(($responses->where('consent_given', true)->count() / max($responses->count(), 1)) * 100, 2),
            'filters' => [
                'program' => $program,
                'year_level' => $yearLevel,
            ]
        ];
    }

    private function generatePdfHtml($responses, $analytics, $program, $yearLevel)
    {
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1, h2 { color: #333; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .analytics { background-color: #f9f9f9; padding: 15px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <h1>Survey Responses Report</h1>
            <p><strong>Generated on:</strong> ' . now()->format('Y-m-d H:i:s') . '</p>
            <p><strong>Program:</strong> ' . ($program ?: 'All Programs') . '</p>
            <p><strong>Year Level:</strong> ' . ($yearLevel ?: 'All Years') . '</p>

            <div class="analytics">
                <h2>Analytics Summary</h2>
                <p><strong>Total Responses:</strong> ' . $analytics['total_responses'] . '</p>
                <p><strong>Consent Rate:</strong> ' . $analytics['consent_rate'] . '%</p>
                <h3>Average Ratings</h3>
                <ul>
                    <li>Course Content: ' . $analytics['average_ratings']['course_content'] . '</li>
                    <li>Facilities: ' . $analytics['average_ratings']['facilities'] . '</li>
                    <li>Support Services: ' . $analytics['average_ratings']['support_services'] . '</li>
                    <li>Overall Satisfaction: ' . $analytics['average_ratings']['overall_satisfaction'] . '</li>
                </ul>
            </div>

            <h2>Response Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Anonymous ID</th>
                        <th>Program</th>
                        <th>Year Level</th>
                        <th>Course Content</th>
                        <th>Facilities</th>
                        <th>Support Services</th>
                        <th>Overall</th>
                        <th>Consent</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($responses as $response) {
            $html .= '
                    <tr>
                        <td>' . $response->anonymous_id . '</td>
                        <td>' . $response->program . '</td>
                        <td>' . $response->year_level . '</td>
                        <td>' . $response->course_content_rating . '</td>
                        <td>' . $response->facilities_rating . '</td>
                        <td>' . $response->support_services_rating . '</td>
                        <td>' . $response->overall_satisfaction . '</td>
                        <td>' . ($response->consent_given ? 'Yes' : 'No') . '</td>
                        <td>' . $response->created_at->format('Y-m-d') . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        return $html;
    }

    private function generateAnalyticsPdfHtml($analytics, $program, $yearLevel)
    {
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1, h2 { color: #333; }
                .analytics { background-color: #f9f9f9; padding: 15px; margin: 20px 0; }
                .metric { margin: 10px 0; }
            </style>
        </head>
        <body>
            <h1>Survey Analytics Report</h1>
            <p><strong>Generated on:</strong> ' . now()->format('Y-m-d H:i:s') . '</p>
            <p><strong>Program:</strong> ' . ($program ?: 'All Programs') . '</p>
            <p><strong>Year Level:</strong> ' . ($yearLevel ?: 'All Years') . '</p>

            <div class="analytics">
                <h2>Summary Metrics</h2>
                <div class="metric"><strong>Total Responses:</strong> ' . $analytics['total_responses'] . '</div>
                <div class="metric"><strong>Consent Rate:</strong> ' . $analytics['consent_rate'] . '%</div>

                <h3>Average Ratings</h3>
                <div class="metric">Course Content: ' . $analytics['average_ratings']['course_content'] . '/5</div>
                <div class="metric">Facilities: ' . $analytics['average_ratings']['facilities'] . '/5</div>
                <div class="metric">Support Services: ' . $analytics['average_ratings']['support_services'] . '/5</div>
                <div class="metric">Overall Satisfaction: ' . $analytics['average_ratings']['overall_satisfaction'] . '/5</div>
            </div>
        </body>
        </html>';

        return $html;
    }
}
