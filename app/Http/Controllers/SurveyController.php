<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    public function submitResponse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_number' => 'required|string|unique:survey_responses',
            'program' => 'required|in:BSIT,BSIT-BA',
            'year_level' => 'required|integer|min:1|max:4',
            'course_content_rating' => 'required|integer|min:1|max:5',
            'facilities_rating' => 'required|integer|min:1|max:5',
            'support_services_rating' => 'required|integer|min:1|max:5',
            'overall_satisfaction' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:1000',
            'consent_given' => 'required|boolean|accepted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['ip_address'] = $request->ip();

        $response = SurveyResponse::create($data);

        return response()->json([
            'message' => 'Survey response submitted successfully',
            'data' => $response->makeHidden(['student_number', 'comments', 'ip_address'])
        ], 201);
    }

    public function getAnalytics(Request $request)
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

        if ($responses->isEmpty()) {
            return response()->json([
                'message' => 'No survey responses found',
                'data' => []
            ]);
        }

        $analytics = [
            'total_responses' => $responses->count(),
            'average_ratings' => [
                'course_content' => round($responses->avg('course_content_rating'), 2),
                'facilities' => round($responses->avg('facilities_rating'), 2),
                'support_services' => round($responses->avg('support_services_rating'), 2),
                'overall_satisfaction' => round($responses->avg('overall_satisfaction'), 2),
            ],
            'highest_ratings' => [
                'course_content' => $responses->max('course_content_rating'),
                'facilities' => $responses->max('facilities_rating'),
                'support_services' => $responses->max('support_services_rating'),
                'overall_satisfaction' => $responses->max('overall_satisfaction'),
            ],
            'lowest_ratings' => [
                'course_content' => $responses->min('course_content_rating'),
                'facilities' => $responses->min('facilities_rating'),
                'support_services' => $responses->min('support_services_rating'),
                'overall_satisfaction' => $responses->min('overall_satisfaction'),
            ],
            'distribution' => [
                'program' => $responses->groupBy('program')->map->count(),
                'year_level' => $responses->groupBy('year_level')->map->count(),
            ],
            'consent_rate' => round(($responses->where('consent_given', true)->count() / $responses->count()) * 100, 2),
        ];

        return response()->json([
            'message' => 'Analytics retrieved successfully',
            'data' => $analytics
        ]);
    }

    public function getAllResponses(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $program = $request->query('program');
        $yearLevel = $request->query('year_level');

        $query = SurveyResponse::query();

        if ($program) {
            $query->where('program', $program);
        }

        if ($yearLevel) {
            $query->where('year_level', $yearLevel);
        }

        $responses = $query->paginate($perPage);

        // Add anonymous IDs for privacy
        $responses->getCollection()->transform(function ($response) {
            $response->anonymous_id = $response->anonymous_id;
            return $response->makeHidden(['student_number', 'comments', 'ip_address']);
        });

        return response()->json([
            'message' => 'Survey responses retrieved successfully',
            'data' => $responses
        ]);
    }

    public function getResponse($id)
    {
        $response = SurveyResponse::findOrFail($id);

        return response()->json([
            'message' => 'Survey response retrieved successfully',
            'data' => $response
        ]);
    }

    public function deleteResponse($id, Request $request)
    {
        $response = SurveyResponse::findOrFail($id);

        // Log the deletion
        $request->user()->auditLogs()->create([
            'action' => 'delete_response',
            'description' => "Deleted survey response for student {$response->student_number}",
            'ip_address' => $request->ip(),
            'old_values' => $response->toArray(),
        ]);

        $response->delete();

        return response()->json([
            'message' => 'Survey response deleted successfully'
        ]);
    }
}
