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
            'learner_needs_index' => 'required|numeric|min:1|max:5',
            'satisfaction_score' => 'required|numeric|min:1|max:5',
            'success_index' => 'required|numeric|min:1|max:5',
            'safety_index' => 'required|numeric|min:1|max:5',
            'wellbeing_index' => 'required|numeric|min:1|max:5',
            'overall_satisfaction' => 'required|integer|min:1|max:5',
        ]);

        $prediction = $this->aiService->predictCompliance($request->all());

        return response()->json([
            'message' => 'ISO 21001 Compliance prediction generated successfully',
            'data' => $prediction
        ]);
    }

    public function clusterResponses(Request $request)
    {
        $track = $request->query('track');
        $gradeLevel = $request->query('grade_level');
        $academicYear = $request->query('academic_year');
        $semester = $request->query('semester');
        $k = $request->query('clusters', 3);

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
                'message' => 'No survey responses found for clustering',
                'data' => []
            ], 404);
        }

        $clusters = $this->aiService->clusterResponses($responses, $k);

        return response()->json([
            'message' => 'ISO 21001 Response clustering completed successfully',
            'data' => $clusters
        ]);
    }

    public function analyzeSentiment(Request $request)
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

        // Combine all three feedback fields
        $textFields = ['positive_aspects', 'improvement_suggestions', 'additional_comments'];
        $comments = $query->where(function($q) use ($textFields) {
            foreach ($textFields as $field) {
                $q->orWhereNotNull($field);
            }
        })->get()
        ->map(function($response) use ($textFields) {
            $texts = [];
            foreach ($textFields as $field) {
                if ($response->$field) {
                    $texts[] = $response->$field; // Decryption handled by model accessors
                }
            }
            return implode(' ', $texts);
        })
        ->filter()
        ->toArray();

        if (empty($comments)) {
            return response()->json([
                'message' => 'No feedback found for sentiment analysis',
                'data' => ['sentiment' => 'Neutral', 'score' => 0]
            ]);
        }

        $sentiment = $this->aiService->analyzeSentiment($comments);

        return response()->json([
            'message' => 'ISO 21001 Sentiment analysis completed successfully',
            'data' => $sentiment
        ]);
    }

    public function extractKeywords(Request $request)
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

        // Combine all three feedback fields
        $textFields = ['positive_aspects', 'improvement_suggestions', 'additional_comments'];
        $comments = $query->where(function($q) use ($textFields) {
            foreach ($textFields as $field) {
                $q->orWhereNotNull($field);
            }
        })->get()
        ->map(function($response) use ($textFields) {
            $texts = [];
            foreach ($textFields as $field) {
                if ($response->$field) {
                    $texts[] = $response->$field; // Decryption handled by model accessors
                }
            }
            return implode(' ', $texts);
        })
        ->filter()
        ->toArray();

        if (empty($comments)) {
            return response()->json([
                'message' => 'No feedback found for keyword extraction',
                'data' => []
            ]);
        }

        $minFrequency = $request->query('min_frequency', 2);
        $keywords = $this->aiService->extractKeywords($comments, $minFrequency);

        return response()->json([
            'message' => 'ISO 21001 Keyword extraction completed successfully',
            'data' => $keywords
        ]);
    }

    public function getComplianceRiskMeter(Request $request)
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

        if ($responses->isEmpty()) {
            return response()->json([
                'message' => 'No data available for ISO 21001 compliance risk assessment',
                'data' => ['risk_level' => 'Unknown', 'score' => 0]
            ]);
        }

        // Calculate ISO 21001 composite indices
        $learnerNeedsIndex = round((
            $responses->avg('curriculum_relevance_rating') +
            $responses->avg('learning_pace_appropriateness') +
            $responses->avg('individual_support_availability') +
            $responses->avg('learning_style_accommodation')
        ) / 4, 2);

        $satisfactionIndex = round((
            $responses->avg('teaching_quality_rating') +
            $responses->avg('learning_environment_rating') +
            $responses->avg('peer_interaction_satisfaction') +
            $responses->avg('extracurricular_satisfaction')
        ) / 4, 2);

        $successIndex = round((
            $responses->avg('academic_progress_rating') +
            $responses->avg('skill_development_rating') +
            $responses->avg('critical_thinking_improvement') +
            $responses->avg('problem_solving_confidence')
        ) / 4, 2);

        $safetyIndex = round((
            $responses->avg('physical_safety_rating') +
            $responses->avg('psychological_safety_rating') +
            $responses->avg('bullying_prevention_effectiveness') +
            $responses->avg('emergency_preparedness_rating')
        ) / 4, 2);

        $wellbeingIndex = round((
            $responses->avg('mental_health_support_rating') +
            $responses->avg('stress_management_support') +
            $responses->avg('physical_health_support') +
            $responses->avg('overall_wellbeing_rating')
        ) / 4, 2);

        $overallSatisfaction = round($responses->avg('overall_satisfaction'), 2);

        // Weighted ISO 21001 Compliance Score (weights can be adjusted based on organizational priorities)
        $weightedScore = (
            $learnerNeedsIndex * 0.15 +
            $satisfactionIndex * 0.25 +
            $successIndex * 0.20 +
            $safetyIndex * 0.20 +
            $wellbeingIndex * 0.15 +
            $overallSatisfaction * 0.05
        );

        $riskScore = (5 - $weightedScore) / 4 * 100; // Convert to percentage

        if ($riskScore < 20) {
            $riskLevel = 'Low Risk';
            $color = 'Green';
        } elseif ($riskScore < 50) {
            $riskLevel = 'Medium Risk';
            $color = 'Yellow';
        } else {
            $riskLevel = 'High Risk';
            $color = 'Red';
        }

        return response()->json([
            'message' => 'ISO 21001 Compliance risk assessment completed successfully',
            'data' => [
                'risk_level' => $riskLevel,
                'risk_score' => round($riskScore, 2),
                'color' => $color,
                'weighted_compliance_score' => round($weightedScore, 2),
                'individual_indices' => [
                    'learner_needs' => $learnerNeedsIndex,
                    'satisfaction' => $satisfactionIndex,
                    'success' => $successIndex,
                    'safety' => $safetyIndex,
                    'wellbeing' => $wellbeingIndex,
                    'overall_satisfaction' => $overallSatisfaction,
                ],
                'total_responses' => $responses->count()
            ]
        ]);
    }
}
