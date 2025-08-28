<?php

namespace App\Http\Controllers;

use App\Models\SurveyResponse;
use App\Services\AIService;
use Illuminate\Http\Request;

class AIController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function predictCompliance(Request $request)
    {
        $request->validate([
            'course_content_rating' => 'required|integer|min:1|max:5',
            'facilities_rating' => 'required|integer|min:1|max:5',
            'support_services_rating' => 'required|integer|min:1|max:5',
            'overall_satisfaction' => 'required|integer|min:1|max:5',
        ]);

        $prediction = $this->aiService->predictCompliance($request->all());

        return response()->json([
            'message' => 'Compliance prediction generated successfully',
            'data' => $prediction
        ]);
    }

    public function clusterResponses(Request $request)
    {
        $program = $request->query('program');
        $yearLevel = $request->query('year_level');
        $k = $request->query('clusters', 3);

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
                'message' => 'No survey responses found for clustering',
                'data' => []
            ], 404);
        }

        $clusters = $this->aiService->clusterResponses($responses, $k);

        return response()->json([
            'message' => 'Response clustering completed successfully',
            'data' => $clusters
        ]);
    }

    public function analyzeSentiment(Request $request)
    {
        $comments = SurveyResponse::whereNotNull('comments')
            ->pluck('comments')
            ->map(function($comment) {
                return decrypt($comment);
            })
            ->filter()
            ->toArray();

        if (empty($comments)) {
            return response()->json([
                'message' => 'No comments found for sentiment analysis',
                'data' => ['sentiment' => 'Neutral', 'score' => 0]
            ]);
        }

        $sentiment = $this->aiService->analyzeSentiment($comments);

        return response()->json([
            'message' => 'Sentiment analysis completed successfully',
            'data' => $sentiment
        ]);
    }

    public function extractKeywords(Request $request)
    {
        $comments = SurveyResponse::whereNotNull('comments')
            ->pluck('comments')
            ->map(function($comment) {
                return decrypt($comment);
            })
            ->filter()
            ->toArray();

        if (empty($comments)) {
            return response()->json([
                'message' => 'No comments found for keyword extraction',
                'data' => []
            ]);
        }

        $minFrequency = $request->query('min_frequency', 2);
        $keywords = $this->aiService->extractKeywords($comments, $minFrequency);

        return response()->json([
            'message' => 'Keyword extraction completed successfully',
            'data' => $keywords
        ]);
    }

    public function getComplianceRiskMeter(Request $request)
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
                'message' => 'No data available for compliance risk assessment',
                'data' => ['risk_level' => 'Unknown', 'score' => 0]
            ]);
        }

        $totalScore = 0;
        $count = 0;

        foreach ($responses as $response) {
            $avgRating = ($response->course_content_rating +
                         $response->facilities_rating +
                         $response->support_services_rating +
                         $response->overall_satisfaction) / 4;
            $totalScore += $avgRating;
            $count++;
        }

        $averageScore = $totalScore / $count;
        $riskScore = (5 - $averageScore) / 4 * 100; // Convert to percentage

        if ($riskScore < 20) {
            $riskLevel = 'Low';
            $color = 'Green';
        } elseif ($riskScore < 50) {
            $riskLevel = 'Medium';
            $color = 'Yellow';
        } else {
            $riskLevel = 'High';
            $color = 'Red';
        }

        return response()->json([
            'message' => 'Compliance risk assessment completed successfully',
            'data' => [
                'risk_level' => $riskLevel,
                'risk_score' => round($riskScore, 2),
                'color' => $color,
                'average_score' => round($averageScore, 2),
                'total_responses' => $count
            ]
        ]);
    }
}
