<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SurveyController extends Controller
{
    public function submitResponse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|string|unique:survey_responses',
            'track' => 'required|in:STEM',
            'grade_level' => 'required|integer|in:11,12',
            'academic_year' => 'required|string|max:9',
            'semester' => 'required|in:1st,2nd',
            // ISO 21001 Learner Needs Assessment (1-5 scale)
            'curriculum_relevance_rating' => 'required|integer|min:1|max:5',
            'learning_pace_appropriateness' => 'required|integer|min:1|max:5',
            'individual_support_availability' => 'required|integer|min:1|max:5',
            'learning_style_accommodation' => 'required|integer|min:1|max:5',
            // ISO 21001 Learner Satisfaction Metrics (1-5 scale)
            'teaching_quality_rating' => 'required|integer|min:1|max:5',
            'learning_environment_rating' => 'required|integer|min:1|max:5',
            'peer_interaction_satisfaction' => 'required|integer|min:1|max:5',
            'extracurricular_satisfaction' => 'required|integer|min:1|max:5',
            // ISO 21001 Learner Success Indicators (1-5 scale)
            'academic_progress_rating' => 'required|integer|min:1|max:5',
            'skill_development_rating' => 'required|integer|min:1|max:5',
            'critical_thinking_improvement' => 'required|integer|min:1|max:5',
            'problem_solving_confidence' => 'required|integer|min:1|max:5',
            // ISO 21001 Learner Safety Assessment (1-5 scale)
            'physical_safety_rating' => 'required|integer|min:1|max:5',
            'psychological_safety_rating' => 'required|integer|min:1|max:5',
            'bullying_prevention_effectiveness' => 'required|integer|min:1|max:5',
            'emergency_preparedness_rating' => 'required|integer|min:1|max:5',
            // ISO 21001 Learner Wellbeing Metrics (1-5 scale)
            'mental_health_support_rating' => 'required|integer|min:1|max:5',
            'stress_management_support' => 'required|integer|min:1|max:5',
            'physical_health_support' => 'required|integer|min:1|max:5',
            'overall_wellbeing_rating' => 'required|integer|min:1|max:5',
            // Overall Satisfaction and Feedback
            'overall_satisfaction' => 'required|integer|min:1|max:5',
            'positive_aspects' => 'nullable|string|max:1000',
            'improvement_suggestions' => 'nullable|string|max:1000',
            'additional_comments' => 'nullable|string|max:1000',
            // Consent
            'consent_given' => 'required|boolean|accepted',
            // Indirect metrics are optional for now
            'attendance_rate' => 'nullable|numeric|min:0|max:100',
            'grade_average' => 'nullable|numeric|min:0|max:4.0',
            'participation_score' => 'nullable|integer|min:0|max:100',
            'extracurricular_hours' => 'nullable|integer|min:0',
            'counseling_sessions' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            // Log validation failure for audit (ISO 21001:8.2.4 - Traceability)
            Log::warning('ISO 21001 Survey submission validation failed', [
                'ip' => $request->ip(),
                'errors' => $validator->errors()->toArray()
            ]);

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();

        // Let model mutators handle encryption - don't encrypt in controller to avoid double encryption
        $data['ip_address'] = $request->ip();

        $response = SurveyResponse::create($data);

        // Log successful submission for audit trail (ISO 21001:8.2.4)
        if (Auth::check() && Auth::user()->role === 'admin') {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'submit_survey_response',
                'description' => 'Processed ISO 21001 survey response submission',
                'ip_address' => $request->ip(),
                'new_values' => ['response_id' => $response->id],
            ]);
        } else {
            // Log anonymous submission
            AuditLog::create([
                'user_id' => null,
                'action' => 'submit_survey_response',
                'description' => 'Submitted ISO 21001 survey response (anonymous)',
                'ip_address' => $request->ip(),
                'new_values' => ['response_id' => $response->id],
            ]);
        }

        return response()->json([
            'message' => 'Survey response submitted successfully',
            'data' => $response->makeHidden(['student_id', 'positive_aspects', 'improvement_suggestions', 'additional_comments', 'ip_address'])
        ], 201);
    }

    public function getAnalytics(Request $request)
    {
        $track = $request->query('track');
        $gradeLevel = $request->query('grade_level');
        $academicYear = $request->query('academic_year');
        $semester = $request->query('semester');

        // Log analytics access for audit (ISO 21001:8.2.4 - Data access traceability)
        if (Auth::check() && Auth::user()->role === 'admin') {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'view_analytics',
                'description' => 'Accessed ISO 21001 analytics dashboard',
                'ip_address' => $request->ip(),
                'new_values' => ['query_params' => $request->query()],
            ]);
        }

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

        if ($responses->isEmpty()) {
            return response()->json([
                'message' => 'No survey responses found',
                'data' => []
            ]);
        }

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

        $analytics = [
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
            'consent_rate' => round(($responses->where('consent_given', true)->count() / $responses->count()) * 100, 2),
        ];

        return response()->json([
            'message' => 'ISO 21001 Analytics retrieved successfully',
            'data' => $analytics
        ]);
    }

    public function getAllResponses(Request $request)
    {
        $perPage = $request->query('per_page', 15);
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

        $responses = $query->paginate($perPage);

        // Add anonymous IDs for privacy
        $responses->getCollection()->transform(function ($response) {
            $response->anonymous_id = $response->anonymous_id;
            return $response->makeHidden(['student_id', 'positive_aspects', 'improvement_suggestions', 'additional_comments', 'ip_address']);
        });

        return response()->json([
            'message' => 'Survey responses retrieved successfully',
            'data' => $responses
        ]);
    }

    public function getResponse($id)
    {
        $response = SurveyResponse::findOrFail($id);

        // Ensure sensitive fields are hidden and add anonymous_id
        $sanitizedResponse = $response->makeHidden(['student_id', 'positive_aspects', 'improvement_suggestions', 'additional_comments', 'ip_address']);
        $sanitizedResponse->anonymous_id = $response->anonymous_id;

        return response()->json([
            'message' => 'Survey response retrieved successfully',
            'data' => $sanitizedResponse
        ]);
    }

    public function deleteResponse($id, Request $request)
    {
        $response = SurveyResponse::findOrFail($id);

        // Log the deletion
        $request->user()->auditLogs()->create([
            'action' => 'delete_response',
            'description' => "Deleted survey response for student {$response->student_id}",
            'ip_address' => $request->ip(),
            'old_values' => $response->toArray(),
        ]);

        $response->delete();

        return response()->json([
            'message' => 'Survey response deleted successfully'
        ]);
    }
}
