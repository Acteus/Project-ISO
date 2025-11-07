<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurveyResponse;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class IndirectMetricsController extends Controller
{
    /**
     * Display the indirect metrics upload page
     */
    public function index()
    {
        $admin = session('admin');
        
        if (!$admin) {
            return redirect()->route('student.login')->with('error', 'Please login as admin to access this page.');
        }

        // Get statistics about metrics coverage
        $totalResponses = SurveyResponse::count();
        $responsesWithMetrics = SurveyResponse::whereNotNull('attendance_rate')
            ->orWhereNotNull('grade_average')
            ->orWhereNotNull('participation_score')
            ->count();
        $coveragePercentage = $totalResponses > 0 ? round(($responsesWithMetrics / $totalResponses) * 100, 1) : 0;

        return view('admin.indirect-metrics', [
            'admin' => $admin,
            'totalResponses' => $totalResponses,
            'responsesWithMetrics' => $responsesWithMetrics,
            'coveragePercentage' => $coveragePercentage,
        ]);
    }

    /**
     * Handle CSV file upload for indirect metrics
     */
    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240', // 10MB max
        ]);

        $admin = session('admin');
        if (!$admin) {
            return back()->with('error', 'Admin session expired. Please login again.');
        }

        try {
            $file = $request->file('csv_file');
            $path = $file->getRealPath();
            
            // Read CSV file
            $data = array_map('str_getcsv', file($path));
            $header = array_shift($data); // Remove header row
            
            // Expected columns: student_id, attendance_rate, grade_average, participation_score, extracurricular_hours, counseling_sessions
            $expectedColumns = ['student_id', 'attendance_rate', 'grade_average', 'participation_score', 'extracurricular_hours', 'counseling_sessions'];
            
            // Normalize header (trim, lowercase)
            $header = array_map(function($col) {
                return strtolower(trim($col));
            }, $header);

            // Validate header columns
            $missingColumns = array_diff($expectedColumns, $header);
            if (!empty($missingColumns)) {
                return back()->with('error', 'CSV file is missing required columns: ' . implode(', ', $missingColumns));
            }

            // Get column indices
            $columnIndices = [];
            foreach ($expectedColumns as $col) {
                $columnIndices[$col] = array_search($col, $header);
            }

            $processed = 0;
            $updated = 0;
            $errors = [];
            $skipped = 0;

            DB::beginTransaction();

            foreach ($data as $rowIndex => $row) {
                $processed++;
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    $skipped++;
                    continue;
                }

                try {
                    // Extract data
                    $studentId = trim($row[$columnIndices['student_id']] ?? '');
                    
                    if (empty($studentId)) {
                        $errors[] = "Row " . ($rowIndex + 2) . ": Missing student_id";
                        continue;
                    }

                    // Find survey responses for this student
                    $responses = SurveyResponse::where('student_id', $studentId)->get();
                    
                    if ($responses->isEmpty()) {
                        $errors[] = "Row " . ($rowIndex + 2) . ": No survey responses found for student_id: {$studentId}";
                        continue;
                    }

                    // Prepare metrics data
                    $metrics = [];
                    
                    if (isset($columnIndices['attendance_rate']) && !empty($row[$columnIndices['attendance_rate']])) {
                        $attendance = floatval($row[$columnIndices['attendance_rate']]);
                        if ($attendance >= 0 && $attendance <= 100) {
                            $metrics['attendance_rate'] = $attendance;
                        } else {
                            $errors[] = "Row " . ($rowIndex + 2) . ": Invalid attendance_rate (must be 0-100)";
                        }
                    }

                    if (isset($columnIndices['grade_average']) && !empty($row[$columnIndices['grade_average']])) {
                        $grade = floatval($row[$columnIndices['grade_average']]);
                        if ($grade >= 0 && $grade <= 5) {
                            $metrics['grade_average'] = $grade;
                        } else {
                            $errors[] = "Row " . ($rowIndex + 2) . ": Invalid grade_average (must be 0-5)";
                        }
                    }

                    if (isset($columnIndices['participation_score']) && !empty($row[$columnIndices['participation_score']])) {
                        $participation = intval($row[$columnIndices['participation_score']]);
                        if ($participation >= 0 && $participation <= 100) {
                            $metrics['participation_score'] = $participation;
                        } else {
                            $errors[] = "Row " . ($rowIndex + 2) . ": Invalid participation_score (must be 0-100)";
                        }
                    }

                    if (isset($columnIndices['extracurricular_hours']) && !empty($row[$columnIndices['extracurricular_hours']])) {
                        $hours = intval($row[$columnIndices['extracurricular_hours']]);
                        if ($hours >= 0) {
                            $metrics['extracurricular_hours'] = $hours;
                        }
                    }

                    if (isset($columnIndices['counseling_sessions']) && !empty($row[$columnIndices['counseling_sessions']])) {
                        $sessions = intval($row[$columnIndices['counseling_sessions']]);
                        if ($sessions >= 0) {
                            $metrics['counseling_sessions'] = $sessions;
                        }
                    }

                    if (empty($metrics)) {
                        $skipped++;
                        continue;
                    }

                    // Update all responses for this student
                    foreach ($responses as $response) {
                        $response->update($metrics);
                        $updated++;
                    }

                } catch (\Exception $e) {
                    $errors[] = "Row " . ($rowIndex + 2) . ": " . $e->getMessage();
                    Log::error('Error processing CSV row', [
                        'row' => $rowIndex + 2,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Log audit trail
            $auditService = app(\App\Services\AuditService::class);
            $auditService->logDataModification(
                'indirect_metrics',
                null,
                'bulk_update',
                null,
                [
                    'admin_id' => $admin->id,
                    'admin_name' => $admin->name,
                    'file_name' => $file->getClientOriginalName(),
                    'processed' => $processed,
                    'updated' => $updated,
                    'skipped' => $skipped,
                    'errors' => count($errors),
                ],
                $request
            );

            DB::commit();

            $message = "Successfully processed {$processed} rows. Updated {$updated} survey responses.";
            if ($skipped > 0) {
                $message .= " Skipped {$skipped} rows.";
            }
            if (!empty($errors)) {
                $message .= " " . count($errors) . " errors occurred.";
            }

            return back()->with('success', $message)
                ->with('errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CSV upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Failed to process CSV file: ' . $e->getMessage());
        }
    }

    /**
     * Handle manual entry of indirect metrics for a single student
     */
    public function updateManual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string',
            'attendance_rate' => 'nullable|numeric|min:0|max:100',
            'grade_average' => 'nullable|numeric|min:0|max:5',
            'participation_score' => 'nullable|integer|min:0|max:100',
            'extracurricular_hours' => 'nullable|integer|min:0',
            'counseling_sessions' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $admin = session('admin');
        if (!$admin) {
            return back()->with('error', 'Admin session expired. Please login again.');
        }

        try {
            $studentId = $request->student_id;
            $responses = SurveyResponse::where('student_id', $studentId)->get();

            if ($responses->isEmpty()) {
                return back()->with('error', "No survey responses found for student_id: {$studentId}");
            }

            $metrics = $request->only([
                'attendance_rate',
                'grade_average',
                'participation_score',
                'extracurricular_hours',
                'counseling_sessions',
            ]);

            // Remove null values
            $metrics = array_filter($metrics, function($value) {
                return $value !== null && $value !== '';
            });

            if (empty($metrics)) {
                return back()->with('error', 'Please provide at least one metric to update.');
            }

            DB::beginTransaction();

            $updated = 0;
            foreach ($responses as $response) {
                $response->update($metrics);
                $updated++;
            }

            // Log audit trail
            $auditService = app(\App\Services\AuditService::class);
            $auditService->logDataModification(
                'indirect_metrics',
                null,
                'manual_update',
                null,
                [
                    'admin_id' => $admin->id,
                    'admin_name' => $admin->name,
                    'student_id' => $studentId,
                    'metrics' => $metrics,
                    'responses_updated' => $updated,
                ],
                $request
            );

            DB::commit();

            return back()->with('success', "Successfully updated {$updated} survey response(s) for student_id: {$studentId}");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Manual metrics update failed', [
                'error' => $e->getMessage(),
                'student_id' => $request->student_id,
            ]);

            return back()->with('error', 'Failed to update metrics: ' . $e->getMessage());
        }
    }

    /**
     * Download CSV template for indirect metrics
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="indirect_metrics_template.csv"',
        ];

        $data = [
            ['student_id', 'attendance_rate', 'grade_average', 'participation_score', 'extracurricular_hours', 'counseling_sessions'],
            ['STU123456', '95.5', '3.8', '88', '12', '2'],
            ['STU789012', '87.2', '3.5', '75', '8', '1'],
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

