<?php

namespace App\Http\Controllers;

use App\Exports\SurveyResponsesExport;
use App\Models\SurveyResponse;
use App\Models\AuditLog;
use App\Services\AIService;
use App\Services\VisualizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $track = $request->query('track');
        $gradeLevel = $request->query('grade_level');
        $academicYear = $request->query('academic_year');
        $semester = $request->query('semester');

        $filename = 'survey_responses_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        // Log the export activity (ISO 21001:8.2.4 - Traceability)
        if (Auth::check() && Auth::user()->role === 'admin') {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'export_excel',
                'description' => "Exported ISO 21001 survey responses to Excel (Track: " . ($track ?: 'All') . ", Grade: " . ($gradeLevel ?: 'All') . ", Year: " . ($academicYear ?: 'All') . ", Semester: " . ($semester ?: 'All') . ")",
                'ip_address' => $request->ip(),
                'new_values' => [
                    'track' => $track,
                    'grade_level' => $gradeLevel,
                    'academic_year' => $academicYear,
                    'semester' => $semester,
                ],
            ]);
        }

        return Excel::download(new SurveyResponsesExport($track, $gradeLevel, $academicYear, $semester), $filename);
    }

    public function exportCsv(Request $request)
    {
        $track = $request->query('track');
        $gradeLevel = $request->query('grade_level');
        $academicYear = $request->query('academic_year');
        $semester = $request->query('semester');

        $filename = 'survey_responses_' . now()->format('Y-m-d_H-i-s') . '.csv';

        // Log the export activity (ISO 21001:8.2.4 - Traceability)
        if (Auth::check() && Auth::user()->role === 'admin') {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'export_csv',
                'description' => "Exported ISO 21001 survey responses to CSV (Track: " . ($track ?: 'All') . ", Grade: " . ($gradeLevel ?: 'All') . ", Year: " . ($academicYear ?: 'All') . ", Semester: " . ($semester ?: 'All') . ")",
                'ip_address' => $request->ip(),
                'new_values' => [
                    'track' => $track,
                    'grade_level' => $gradeLevel,
                    'academic_year' => $academicYear,
                    'semester' => $semester,
                ],
            ]);
        }

        return Excel::download(new SurveyResponsesExport($track, $gradeLevel, $academicYear, $semester), $filename, \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPdf(Request $request)
    {
        $track = $request->query('track');
        $gradeLevel = $request->query('grade_level');
        $academicYear = $request->query('academic_year');
        $semester = $request->query('semester');

        $query = SurveyResponse::query();

        if ($track) {
            $query->where('track', $track);
        }

        if ($gradeLevel) {
            $query->where('grade_level', $gradeLevel);
        }

        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }

        if ($semester) {
            $query->where('semester', $semester);
        }

        $responses = $query->get();
        $analytics = $this->getAnalyticsData($responses, $track, $gradeLevel, $academicYear, $semester);

        // Generate HTML content for PDF
        $html = $this->generatePdfHtml($responses, $analytics, $track, $gradeLevel, $academicYear, $semester);

        // Configure Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'iso_21001_survey_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        // Log the export activity (ISO 21001:8.2.4 - Traceability)
        if (Auth::check() && Auth::user()->role === 'admin') {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'export_pdf',
                'description' => "Exported ISO 21001 survey report to PDF (Track: " . ($track ?: 'All') . ", Grade: " . ($gradeLevel ?: 'All') . ", Year: " . ($academicYear ?: 'All') . ", Semester: " . ($semester ?: 'All') . ")",
                'ip_address' => $request->ip(),
                'new_values' => [
                    'track' => $track,
                    'grade_level' => $gradeLevel,
                    'academic_year' => $academicYear,
                    'semester' => $semester,
                ],
            ]);
        }

        return $dompdf->stream($filename);
    }

    public function exportAnalyticsReport(Request $request)
    {
        $track = $request->query('track');
        $gradeLevel = $request->query('grade_level');
        $academicYear = $request->query('academic_year');
        $semester = $request->query('semester');
        $format = $request->query('format', 'pdf'); // pdf or excel

        $query = SurveyResponse::query();

        if ($track) {
            $query->where('track', $track);
        }

        if ($gradeLevel) {
            $query->where('grade_level', $gradeLevel);
        }

        if ($academicYear) {
            $query->where('academic_year', $academicYear);
        }

        if ($semester) {
            $query->where('semester', $semester);
        }

        $responses = $query->get();
        $analytics = $this->getAnalyticsData($responses, $track, $gradeLevel, $academicYear, $semester);

        // Only support PDF format for analytics reports
        if ($format === 'excel') {
            return response()->json([
                'message' => 'Excel format not available for analytics reports. Use PDF format.',
                'available_formats' => ['pdf']
            ], 400);
        }
            // Generate PDF analytics report
            $html = $this->generateAnalyticsPdfHtml($analytics, $track, $gradeLevel, $academicYear, $semester);

            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $options->set('isHtml5ParserEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $filename = 'iso_21001_analytics_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';

            // Log the export activity (ISO 21001:8.2.4 - Traceability)
            if (Auth::check() && Auth::user()->role === 'admin') {
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'export_analytics_pdf',
                    'description' => "Exported ISO 21001 analytics report to PDF (Track: " . ($track ?: 'All') . ", Grade: " . ($gradeLevel ?: 'All') . ", Year: " . ($academicYear ?: 'All') . ", Semester: " . ($semester ?: 'All') . ")",
                    'ip_address' => $request->ip(),
                    'new_values' => [
                        'track' => $track,
                        'grade_level' => $gradeLevel,
                        'academic_year' => $academicYear,
                        'semester' => $semester,
                    ],
                ]);
            }

            return $dompdf->stream($filename);
    }

    private function getAnalyticsData($responses, $track, $gradeLevel, $academicYear, $semester)
    {
        // ISO 21001 Composite Scores
        $learnerNeedsIndex = round(
            ($responses->avg('curriculum_relevance_rating') +
             $responses->avg('learning_pace_appropriateness') +
             $responses->avg('individual_support_availability') +
             $responses->avg('learning_style_accommodation')) / 4, 2
        );

        $satisfactionScore = round(
            ($responses->avg('teaching_quality_rating') +
             $responses->avg('learning_environment_rating') +
             $responses->avg('peer_interaction_satisfaction') +
             $responses->avg('extracurricular_satisfaction')) / 4, 2
        );

        $successIndex = round(
            ($responses->avg('academic_progress_rating') +
             $responses->avg('skill_development_rating') +
             $responses->avg('critical_thinking_improvement') +
             $responses->avg('problem_solving_confidence')) / 4, 2
        );

        $safetyIndex = round(
            ($responses->avg('physical_safety_rating') +
             $responses->avg('psychological_safety_rating') +
             $responses->avg('bullying_prevention_effectiveness') +
             $responses->avg('emergency_preparedness_rating')) / 4, 2
        );

        $wellbeingIndex = round(
            ($responses->avg('mental_health_support_rating') +
             $responses->avg('stress_management_support') +
             $responses->avg('physical_health_support') +
             $responses->avg('overall_wellbeing_rating')) / 4, 2
        );

        // Performance vs Satisfaction Correlation
        $avgSatisfaction = round($responses->avg('overall_satisfaction'), 2);
        $avgGrade = $responses->avg('grade_average') ?? 0;
        $avgAttendance = $responses->avg('attendance_rate') ?? 0;

        return [
            'total_responses' => $responses->count(),
            'iso_21001_indices' => [
                'learner_needs_index' => $learnerNeedsIndex,
                'satisfaction_score' => $satisfactionScore,
                'success_index' => $successIndex,
                'safety_index' => $safetyIndex,
                'wellbeing_index' => $wellbeingIndex,
                'overall_satisfaction' => $avgSatisfaction,
            ],
            'indirect_metrics' => [
                'average_grade' => round($avgGrade, 2),
                'average_attendance_rate' => round($avgAttendance, 2),
                'average_participation_score' => round($responses->avg('participation_score') ?? 0, 2),
                'average_extracurricular_hours' => round($responses->avg('extracurricular_hours') ?? 0, 2),
                'average_counseling_sessions' => round($responses->avg('counseling_sessions') ?? 0, 2),
            ],
            'correlation_analysis' => [
                'satisfaction_vs_performance_correlation' => round($avgSatisfaction * $avgGrade, 2),
                'satisfaction_vs_attendance_correlation' => round($avgSatisfaction * $avgAttendance, 2),
                'safety_vs_attendance_correlation' => round($safetyIndex * $avgAttendance, 2),
                'wellbeing_vs_counseling_correlation' => round($wellbeingIndex * ($responses->avg('counseling_sessions') ?? 0), 2),
            ],
            'distribution' => [
                'track' => $responses->groupBy('track')->map->count(),
                'grade_level' => $responses->groupBy('grade_level')->map->count(),
                'academic_year' => $responses->groupBy('academic_year')->map->count(),
                'semester' => $responses->groupBy('semester')->map->count(),
            ],
            'consent_rate' => round(($responses->where('consent_given', true)->count() / max($responses->count(), 1)) * 100, 2),
            'filters' => [
                'track' => $track,
                'grade_level' => $gradeLevel,
                'academic_year' => $academicYear,
                'semester' => $semester,
            ]
        ];
    }

    private function generatePdfHtml($responses, $analytics, $track, $gradeLevel, $academicYear, $semester)
    {
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1, h2, h3 { color: #333; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; font-size: 10px; }
                th, td { border: 1px solid #ddd; padding: 4px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .analytics { background-color: #f9f9f9; padding: 15px; margin: 20px 0; border-radius: 5px; }
                .index-score { background-color: #e8f4fd; padding: 5px; margin: 2px 0; border-left: 4px solid #2196F3; }
                .correlation-score { background-color: #fff3e0; padding: 5px; margin: 2px 0; border-left: 4px solid #FF9800; }
            </style>
        </head>
        <body>
            <h1>ISO 21001 STEM Survey Responses Report</h1>
            <p><strong>Generated on:</strong> ' . now()->format('Y-m-d H:i:s') . '</p>
            <p><strong>Track:</strong> ' . ($track ?: 'All Tracks') . '</p>
            <p><strong>Grade Level:</strong> ' . ($gradeLevel ?: 'All Grades') . '</p>
            <p><strong>Academic Year:</strong> ' . ($academicYear ?: 'All Years') . '</p>
            <p><strong>Semester:</strong> ' . ($semester ?: 'All Semesters') . '</p>

            <div class="analytics">
                <h2>ISO 21001 Analytics Summary</h2>
                <p><strong>Total Responses:</strong> ' . $analytics['total_responses'] . '</p>
                <p><strong>Consent Rate:</strong> ' . $analytics['consent_rate'] . '%</p>

                <h3>ISO 21001 Indices (1-5 Scale)</h3>
                <div class="index-score"><strong>Learner Needs Index:</strong> ' . $analytics['iso_21001_indices']['learner_needs_index'] . '</div>
                <div class="index-score"><strong>Satisfaction Score:</strong> ' . $analytics['iso_21001_indices']['satisfaction_score'] . '</div>
                <div class="index-score"><strong>Success Index:</strong> ' . $analytics['iso_21001_indices']['success_index'] . '</div>
                <div class="index-score"><strong>Safety Index:</strong> ' . $analytics['iso_21001_indices']['safety_index'] . '</div>
                <div class="index-score"><strong>Wellbeing Index:</strong> ' . $analytics['iso_21001_indices']['wellbeing_index'] . '</div>
                <div class="index-score"><strong>Overall Satisfaction:</strong> ' . $analytics['iso_21001_indices']['overall_satisfaction'] . '</div>

                <h3>Indirect Performance Metrics</h3>
                <div class="index-score"><strong>Average Grade (GPA):</strong> ' . $analytics['indirect_metrics']['average_grade'] . '</div>
                <div class="index-score"><strong>Average Attendance Rate:</strong> ' . $analytics['indirect_metrics']['average_attendance_rate'] . '%</div>
                <div class="index-score"><strong>Average Participation Score:</strong> ' . $analytics['indirect_metrics']['average_participation_score'] . '%</div>
                <div class="index-score"><strong>Average Extracurricular Hours:</strong> ' . $analytics['indirect_metrics']['average_extracurricular_hours'] . ' hours/month</div>
                <div class="index-score"><strong>Average Counseling Sessions:</strong> ' . $analytics['indirect_metrics']['average_counseling_sessions'] . ' sessions</div>

                <h3>Correlation Analysis</h3>
                <div class="correlation-score"><strong>Satisfaction vs Performance:</strong> ' . $analytics['correlation_analysis']['satisfaction_vs_performance_correlation'] . '</div>
                <div class="correlation-score"><strong>Satisfaction vs Attendance:</strong> ' . $analytics['correlation_analysis']['satisfaction_vs_attendance_correlation'] . '</div>
                <div class="correlation-score"><strong>Safety vs Attendance:</strong> ' . $analytics['correlation_analysis']['safety_vs_attendance_correlation'] . '</div>
                <div class="correlation-score"><strong>Wellbeing vs Counseling:</strong> ' . $analytics['correlation_analysis']['wellbeing_vs_counseling_correlation'] . '</div>
            </div>

            <h2>Individual Response Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Anonymous ID</th>
                        <th>Track</th>
                        <th>Grade</th>
                        <th>Year</th>
                        <th>Semester</th>
                        <th>Curriculum</th>
                        <th>Teaching</th>
                        <th>Safety</th>
                        <th>Wellbeing</th>
                        <th>Overall</th>
                        <th>Consent</th>
                        <th>GPA</th>
                        <th>Attendance</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($responses as $response) {
            $html .= '
                    <tr>
                        <td>' . $response->anonymous_id . '</td>
                        <td>' . $response->track . '</td>
                        <td>' . $response->grade_level . '</td>
                        <td>' . $response->academic_year . '</td>
                        <td>' . $response->semester . '</td>
                        <td>' . $response->curriculum_relevance_rating . '</td>
                        <td>' . $response->teaching_quality_rating . '</td>
                        <td>' . $response->physical_safety_rating . '</td>
                        <td>' . $response->mental_health_support_rating . '</td>
                        <td>' . $response->overall_satisfaction . '</td>
                        <td>' . ($response->consent_given ? 'Yes' : 'No') . '</td>
                        <td>' . ($response->grade_average ?? 'N/A') . '</td>
                        <td>' . ($response->attendance_rate ?? 'N/A') . '%</td>
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

    private function generateAnalyticsPdfHtml($analytics, $track, $gradeLevel, $academicYear, $semester)
    {
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1, h2, h3 { color: #333; }
                .analytics { background-color: #f9f9f9; padding: 15px; margin: 20px 0; border-radius: 5px; }
                .metric { margin: 10px 0; padding: 5px; border-left: 3px solid #2196F3; background-color: #f0f8ff; }
                .correlation { margin: 10px 0; padding: 5px; border-left: 3px solid #FF9800; background-color: #fff3e0; }
                .distribution { margin: 10px 0; padding: 5px; border-left: 3px solid #4CAF50; background-color: #e8f5e8; }
            </style>
        </head>
        <body>
            <h1>ISO 21001 Analytics Report - STEM Track</h1>
            <p><strong>Generated on:</strong> ' . now()->format('Y-m-d H:i:s') . '</p>
            <p><strong>Track:</strong> ' . ($track ?: 'All Tracks') . '</p>
            <p><strong>Grade Level:</strong> ' . ($gradeLevel ?: 'All Grades') . '</p>
            <p><strong>Academic Year:</strong> ' . ($academicYear ?: 'All Years') . '</p>
            <p><strong>Semester:</strong> ' . ($semester ?: 'All Semesters') . '</p>

            <div class="analytics">
                <h2>Summary Metrics</h2>
                <div class="metric"><strong>Total Responses:</strong> ' . $analytics['total_responses'] . '</div>
                <div class="metric"><strong>Consent Rate:</strong> ' . $analytics['consent_rate'] . '%</div>

                <h3>ISO 21001 Indices (1-5 Scale)</h3>
                <div class="metric"><strong>Learner Needs Index:</strong> ' . $analytics['iso_21001_indices']['learner_needs_index'] . '/5</div>
                <div class="metric"><strong>Satisfaction Score:</strong> ' . $analytics['iso_21001_indices']['satisfaction_score'] . '/5</div>
                <div class="metric"><strong>Success Index:</strong> ' . $analytics['iso_21001_indices']['success_index'] . '/5</div>
                <div class="metric"><strong>Safety Index:</strong> ' . $analytics['iso_21001_indices']['safety_index'] . '/5</div>
                <div class="metric"><strong>Wellbeing Index:</strong> ' . $analytics['iso_21001_indices']['wellbeing_index'] . '/5</div>
                <div class="metric"><strong>Overall Satisfaction:</strong> ' . $analytics['iso_21001_indices']['overall_satisfaction'] . '/5</div>

                <h3>Indirect Performance Metrics</h3>
                <div class="metric"><strong>Average Grade (GPA):</strong> ' . $analytics['indirect_metrics']['average_grade'] . '/4.0</div>
                <div class="metric"><strong>Average Attendance Rate:</strong> ' . $analytics['indirect_metrics']['average_attendance_rate'] . '%</div>
                <div class="metric"><strong>Average Participation Score:</strong> ' . $analytics['indirect_metrics']['average_participation_score'] . '%</div>
                <div class="metric"><strong>Average Extracurricular Hours:</strong> ' . $analytics['indirect_metrics']['average_extracurricular_hours'] . ' hours/month</div>
                <div class="metric"><strong>Average Counseling Sessions:</strong> ' . $analytics['indirect_metrics']['average_counseling_sessions'] . ' sessions</div>

                <h3>Correlation Analysis</h3>
                <div class="correlation"><strong>Satisfaction vs Performance Correlation:</strong> ' . $analytics['correlation_analysis']['satisfaction_vs_performance_correlation'] . '</div>
                <div class="correlation"><strong>Satisfaction vs Attendance Correlation:</strong> ' . $analytics['correlation_analysis']['satisfaction_vs_attendance_correlation'] . '</div>
                <div class="correlation"><strong>Safety vs Attendance Correlation:</strong> ' . $analytics['correlation_analysis']['safety_vs_attendance_correlation'] . '</div>
                <div class="correlation"><strong>Wellbeing vs Counseling Correlation:</strong> ' . $analytics['correlation_analysis']['wellbeing_vs_counseling_correlation'] . '</div>

                <h3>Response Distribution</h3>
                <div class="distribution"><strong>By Track:</strong> ' . json_encode($analytics['distribution']['track']) . '</div>
                <div class="distribution"><strong>By Grade Level:</strong> ' . json_encode($analytics['distribution']['grade_level']) . '</div>
                <div class="distribution"><strong>By Academic Year:</strong> ' . json_encode($analytics['distribution']['academic_year']) . '</div>
                <div class="distribution"><strong>By Semester:</strong> ' . json_encode($analytics['distribution']['semester']) . '</div>
            </div>
        </body>
        </html>';

        return $html;
    }
}
