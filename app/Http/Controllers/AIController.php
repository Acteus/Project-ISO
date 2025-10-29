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
        $dateFrom = $request->query('date_from');
        $dateTo = $request->query('date_to');

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

        // Date range filtering
        if ($dateFrom && $dateTo) {
            $query->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        } elseif ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom . ' 00:00:00');
        } elseif ($dateTo) {
            $query->where('created_at', '<=', $dateTo . ' 23:59:59');
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

    public function getServiceStatus()
    {
        $flaskClient = app(\App\Services\FlaskAIClient::class);
        $status = $flaskClient->getServiceStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    public function getAIMetrics()
    {
        // Get basic AI metrics (this could be expanded with more detailed metrics)
        $totalPredictions = \App\Models\AuditLog::where('action', 'LIKE', '%ai%')->count();
        $accuracyRate = 85; // Placeholder - would need actual model metrics
        $avgResponseTime = 150; // Placeholder - would need actual timing data

        return response()->json([
            'success' => true,
            'data' => [
                'total_predictions' => $totalPredictions,
                'accuracy_rate' => $accuracyRate,
                'avg_response_time' => $avgResponseTime
            ]
        ]);
    }

    public function runAnalysis(Request $request, $type)
    {
        $flaskClient = app(\App\Services\FlaskAIClient::class);

        try {
            $result = null;

            switch ($type) {
                case 'compliance':
                    // Get sample data from recent survey responses for compliance prediction
                    $recentResponse = \App\Models\SurveyResponse::latest()->first();
                    if ($recentResponse) {
                        $data = [
                            'learner_needs_index' => ($recentResponse->curriculum_relevance_rating + $recentResponse->learning_pace_appropriateness + $recentResponse->individual_support_availability + $recentResponse->learning_style_accommodation) / 4,
                            'satisfaction_score' => ($recentResponse->teaching_quality_rating + $recentResponse->learning_environment_rating + $recentResponse->peer_interaction_satisfaction + $recentResponse->extracurricular_satisfaction) / 4,
                            'success_index' => ($recentResponse->academic_progress_rating + $recentResponse->skill_development_rating + $recentResponse->critical_thinking_improvement + $recentResponse->problem_solving_confidence) / 4,
                            'safety_index' => ($recentResponse->physical_safety_rating + $recentResponse->psychological_safety_rating + $recentResponse->bullying_prevention_effectiveness + $recentResponse->emergency_preparedness_rating) / 4,
                            'wellbeing_index' => ($recentResponse->mental_health_support_rating + $recentResponse->stress_management_support + $recentResponse->physical_health_support + $recentResponse->overall_wellbeing_rating) / 4,
                            'overall_satisfaction' => $recentResponse->overall_satisfaction
                        ];
                        $result = $flaskClient->predictCompliance($data);
                    }
                    break;

                case 'sentiment':
                    // Get recent comments for sentiment analysis
                    $comments = \App\Models\SurveyResponse::whereNotNull('positive_aspects')
                        ->orWhereNotNull('improvement_suggestions')
                        ->orWhereNotNull('additional_comments')
                        ->latest()
                        ->take(10)
                        ->get()
                        ->map(function($response) {
                            $texts = [];
                            if ($response->positive_aspects) $texts[] = $response->positive_aspects;
                            if ($response->improvement_suggestions) $texts[] = $response->improvement_suggestions;
                            if ($response->additional_comments) $texts[] = $response->additional_comments;
                            return implode(' ', $texts);
                        })
                        ->filter()
                        ->take(5)
                        ->toArray();

                    if (!empty($comments)) {
                        $result = $flaskClient->analyzeSentiment($comments);
                    }
                    break;

                case 'clustering':
                    // Get recent responses for clustering - extract only relevant numeric fields
                    $responses = \App\Models\SurveyResponse::latest()->take(20)->get()->map(function($response) {
                        return [
                            'id' => $response->id,
                            'overall_satisfaction' => $response->overall_satisfaction,
                            'curriculum_relevance_rating' => $response->curriculum_relevance_rating,
                            'learning_pace_appropriateness' => $response->learning_pace_appropriateness,
                            'individual_support_availability' => $response->individual_support_availability,
                            'learning_style_accommodation' => $response->learning_style_accommodation,
                            'teaching_quality_rating' => $response->teaching_quality_rating,
                            'learning_environment_rating' => $response->learning_environment_rating,
                            'peer_interaction_satisfaction' => $response->peer_interaction_satisfaction,
                            'extracurricular_satisfaction' => $response->extracurricular_satisfaction,
                            'academic_progress_rating' => $response->academic_progress_rating,
                            'skill_development_rating' => $response->skill_development_rating,
                            'critical_thinking_improvement' => $response->critical_thinking_improvement,
                            'problem_solving_confidence' => $response->problem_solving_confidence,
                            'physical_safety_rating' => $response->physical_safety_rating,
                            'psychological_safety_rating' => $response->psychological_safety_rating,
                            'bullying_prevention_effectiveness' => $response->bullying_prevention_effectiveness,
                            'emergency_preparedness_rating' => $response->emergency_preparedness_rating,
                            'mental_health_support_rating' => $response->mental_health_support_rating,
                            'stress_management_support' => $response->stress_management_support,
                            'physical_health_support' => $response->physical_health_support,
                            'overall_wellbeing_rating' => $response->overall_wellbeing_rating,
                            'attendance_rate' => $response->attendance_rate ?? 85,
                            'grade_average' => $response->grade_average ?? 85,
                            'participation_score' => $response->participation_score ?? 80,
                            'extracurricular_hours' => $response->extracurricular_hours ?? 10,
                            'counseling_sessions' => $response->counseling_sessions ?? 2,
                            'track' => $response->track,
                            'gender' => $response->gender,
                        ];
                    })->toArray();

                    if (count($responses) >= 3) {
                        $result = $flaskClient->clusterStudents($responses, 3);
                    }
                    break;

                case 'performance':
                    // Get sample data for performance prediction
                    $recentResponse = \App\Models\SurveyResponse::latest()->first();
                    if ($recentResponse) {
                        $data = [
                            'curriculum_relevance_rating' => $recentResponse->curriculum_relevance_rating,
                            'learning_pace_appropriateness' => $recentResponse->learning_pace_appropriateness,
                            'individual_support_availability' => $recentResponse->individual_support_availability,
                            'teaching_quality_rating' => $recentResponse->teaching_quality_rating,
                            'attendance_rate' => $recentResponse->attendance_rate ?? 85,
                            'participation_score' => $recentResponse->participation_score ?? 80,
                            'overall_satisfaction' => $recentResponse->overall_satisfaction
                        ];
                        $result = $flaskClient->predictPerformance($data);
                    }
                    break;

                case 'dropout':
                    // Get sample data for dropout risk prediction
                    $recentResponse = \App\Models\SurveyResponse::latest()->first();
                    if ($recentResponse) {
                        $data = [
                            'attendance_rate' => $recentResponse->attendance_rate ?? 75,
                            'overall_satisfaction' => $recentResponse->overall_satisfaction,
                            'academic_progress_rating' => $recentResponse->academic_progress_rating,
                            'physical_safety_rating' => $recentResponse->physical_safety_rating,
                            'psychological_safety_rating' => $recentResponse->psychological_safety_rating,
                            'mental_health_support_rating' => $recentResponse->mental_health_support_rating
                        ];
                        $result = $flaskClient->predictDropoutRisk($data);
                    }
                    break;

                case 'comprehensive':
                    // Get multiple responses for comprehensive analytics to ensure all models can run
                    $responses = \App\Models\SurveyResponse::latest()->take(10)->get();
                    if ($responses->isNotEmpty()) {
                        $recentResponse = $responses->first();

                        // Get comments from multiple responses for sentiment analysis
                        $comments = [];
                        foreach ($responses as $response) {
                            $responseComments = array_filter([$response->positive_aspects, $response->improvement_suggestions, $response->additional_comments]);
                            $comments = array_merge($comments, $responseComments);
                        }

                        // Prepare comprehensive data with all required fields for all models
                        $data = [
                            // Compliance prediction fields
                            'learner_needs_index' => ($recentResponse->curriculum_relevance_rating + $recentResponse->learning_pace_appropriateness + $recentResponse->individual_support_availability + $recentResponse->learning_style_accommodation) / 4,
                            'satisfaction_score' => ($recentResponse->teaching_quality_rating + $recentResponse->learning_environment_rating + $recentResponse->peer_interaction_satisfaction + $recentResponse->extracurricular_satisfaction) / 4,
                            'success_index' => ($recentResponse->academic_progress_rating + $recentResponse->skill_development_rating + $recentResponse->critical_thinking_improvement + $recentResponse->problem_solving_confidence) / 4,
                            'safety_index' => ($recentResponse->physical_safety_rating + $recentResponse->psychological_safety_rating + $recentResponse->bullying_prevention_effectiveness + $recentResponse->emergency_preparedness_rating) / 4,
                            'wellbeing_index' => ($recentResponse->mental_health_support_rating + $recentResponse->stress_management_support + $recentResponse->physical_health_support + $recentResponse->overall_wellbeing_rating) / 4,
                            'overall_satisfaction' => $recentResponse->overall_satisfaction,

                            // Sentiment analysis fields - use comments from multiple responses
                            'comments' => $comments,

                            // Clustering fields - use multiple responses
                            'responses' => $responses->map(function($response) {
                                return [
                                    'id' => $response->id,
                                    'overall_satisfaction' => $response->overall_satisfaction,
                                    'curriculum_relevance_rating' => $response->curriculum_relevance_rating,
                                    'learning_pace_appropriateness' => $response->learning_pace_appropriateness,
                                    'individual_support_availability' => $response->individual_support_availability,
                                    'learning_style_accommodation' => $response->learning_style_accommodation,
                                    'teaching_quality_rating' => $response->teaching_quality_rating,
                                    'learning_environment_rating' => $response->learning_environment_rating,
                                    'peer_interaction_satisfaction' => $response->peer_interaction_satisfaction,
                                    'extracurricular_satisfaction' => $response->extracurricular_satisfaction,
                                    'academic_progress_rating' => $response->academic_progress_rating,
                                    'skill_development_rating' => $response->skill_development_rating,
                                    'critical_thinking_improvement' => $response->critical_thinking_improvement,
                                    'problem_solving_confidence' => $response->problem_solving_confidence,
                                    'physical_safety_rating' => $response->physical_safety_rating,
                                    'psychological_safety_rating' => $response->psychological_safety_rating,
                                    'bullying_prevention_effectiveness' => $response->bullying_prevention_effectiveness,
                                    'emergency_preparedness_rating' => $response->emergency_preparedness_rating,
                                    'mental_health_support_rating' => $response->mental_health_support_rating,
                                    'stress_management_support' => $response->stress_management_support,
                                    'physical_health_support' => $response->physical_health_support,
                                    'overall_wellbeing_rating' => $response->overall_wellbeing_rating,
                                    'attendance_rate' => $response->attendance_rate ?? 85,
                                    'grade_average' => $response->grade_average ?? 85,
                                    'participation_score' => $response->participation_score ?? 80,
                                    'extracurricular_hours' => $response->extracurricular_hours ?? 10,
                                    'counseling_sessions' => $response->counseling_sessions ?? 2,
                                    'track' => $response->track,
                                    'gender' => $response->gender,
                                ];
                            })->toArray(),

                            // Performance prediction fields
                            'curriculum_relevance_rating' => $recentResponse->curriculum_relevance_rating,
                            'learning_pace_appropriateness' => $recentResponse->learning_pace_appropriateness,
                            'individual_support_availability' => $recentResponse->individual_support_availability,
                            'teaching_quality_rating' => $recentResponse->teaching_quality_rating,
                            'attendance_rate' => $recentResponse->attendance_rate ?? 85,
                            'participation_score' => $recentResponse->participation_score ?? 80,

                            // Dropout risk fields
                            'academic_progress_rating' => $recentResponse->academic_progress_rating,
                            'physical_safety_rating' => $recentResponse->physical_safety_rating,
                            'psychological_safety_rating' => $recentResponse->psychological_safety_rating,
                            'mental_health_support_rating' => $recentResponse->mental_health_support_rating,

                            // Clustering parameters - adjust based on number of responses
                            'clusters' => min(3, max(2, count($responses)))
                        ];
                        $result = $flaskClient->getComprehensiveAnalytics($data);
                    }
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Unknown analysis type'
                    ], 400);
            }

            if ($result) {
                // Log the AI analysis for audit trail
                \App\Models\AuditLog::create([
                    'admin_id' => session('admin')->id ?? null,
                    'action' => 'ai_analysis_' . $type,
                    'description' => 'AI analysis performed: ' . $type,
                    'ip_address' => $request->ip(),
                    'new_values' => ['analysis_type' => $type]
                ]);

                // Normalize the result into a consistent JSON shape for the frontend
                $normalized = $this->normalizeAnalysisResult($type, $result);

                return response()->json([
                    'success' => true,
                    'data' => $normalized
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'AI service unavailable or analysis failed'
                ], 503);
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AI Analysis error', [
                'type' => $type,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Analysis failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Normalize various analysis results into consistent JSON shapes
     * so the frontend can rely on predictable keys.
     */
    protected function normalizeAnalysisResult(string $type, $result)
    {
        // Convert objects to associative arrays for easier handling
        if (is_object($result)) {
            $result = json_decode(json_encode($result), true);
        }

        // If result is empty/null, return minimal structure
        if (empty($result)) {
            return $this->getEmptyResultStructure($type);
        }

        // Helper to ensure array structure
        $wrap = function($key, $value) {
            return [$key => $value];
        };

        switch ($type) {
            case 'compliance':
                // Expected fields: prediction, prediction_probability, weighted_score, risk_level, confidence
                $r = is_array($result) ? $result : [];

                // Extract data with proper type casting
                $prediction = $r['prediction'] ?? $r['label'] ?? 'Unknown';
                $prob = isset($r['prediction_probability']) ? (float)$r['prediction_probability'] :
                        (isset($r['probability']) ? (float)$r['probability'] :
                        (isset($r['prob']) ? (float)$r['prob'] : 0.0));
                $weighted = isset($r['weighted_score']) ? (float)$r['weighted_score'] :
                           (isset($r['weighted_compliance_score']) ? (float)$r['weighted_compliance_score'] :
                           (isset($r['score']) ? (float)$r['score'] : 0.0));
                $risk = $r['risk_level'] ?? $r['risk'] ?? 'Unknown';
                $confidence = isset($r['confidence']) ? (float)$r['confidence'] : 0.0;

                return $wrap('prediction', [
                    'prediction' => $prediction,
                    'prediction_probability' => $prob,
                    'weighted_score' => $weighted,
                    'risk_level' => $risk,
                    'confidence' => $confidence
                ]);

            case 'sentiment':
                // Normalize to { sentiment_analysis: [ { text, sentiment, confidence, probabilities } ] }
                $items = [];
                if (is_array($result) && array_values($result) === $result) {
                    foreach ($result as $it) {
                        $itArr = is_array($it) ? $it : (is_object($it) ? (array)$it : ['text' => $it]);
                        $items[] = [
                            'text' => $itArr['text'] ?? $itArr['comment'] ?? $itArr['sentence'] ?? null,
                            'sentiment' => $itArr['sentiment'] ?? $itArr['label'] ?? 'neutral',
                            'confidence' => isset($itArr['confidence']) ? (float)$itArr['confidence'] : (isset($itArr['score']) ? (float)$itArr['score'] : 0.5),
                            'probabilities' => $itArr['probabilities'] ?? $itArr['scores'] ?? null,
                        ];
                    }
                } elseif (is_array($result) && isset($result['sentiment'])) {
                    // Single item shaped result
                    $items[] = [
                        'text' => $result['text'] ?? null,
                        'sentiment' => $result['sentiment'] ?? 'neutral',
                        'confidence' => isset($result['confidence']) ? (float)$result['confidence'] : 0.5,
                        'probabilities' => $result['probabilities'] ?? null,
                    ];
                }
                return $wrap('sentiment_analysis', $items ?: []);

            case 'clustering':
                // Normalize to { clustering_result: { clusters, detailed_clusters, metrics, insights, total_samples } }
                $r = is_array($result) ? $result : [];
                return $wrap('clustering_result', [
                    'clusters' => $r['clusters'] ?? $r['cluster_count'] ?? $r['num_clusters'] ?? 0,
                    'detailed_clusters' => $r['detailed_clusters'] ?? $r['clusters_detail'] ?? $r['clusters_list'] ?? [],
                    'metrics' => $r['metrics'] ?? [],
                    'insights' => $r['insights'] ?? [],
                    'total_samples' => $r['total_samples'] ?? $r['n_samples'] ?? 0,
                    'algorithm' => $r['algorithm'] ?? 'K-Means',
                ]);

            case 'performance':
                $r = is_array($result) ? $result : [];
                return $wrap('prediction', [
                    'prediction' => $r['prediction'] ?? $r['predicted_label'] ?? 'Unknown',
                    'predicted_gpa' => isset($r['predicted_gpa']) ? (float)$r['predicted_gpa'] : (isset($r['predicted_score']) ? (float)$r['predicted_score'] : 0.0),
                    'confidence' => isset($r['confidence']) ? (float)$r['confidence'] : 0.0,
                    'risk_level' => $r['risk_level'] ?? 'Unknown',
                    'model_used' => $r['model'] ?? $r['model_used'] ?? 'ML Model',
                ]);

            case 'dropout':
                $r = is_array($result) ? $result : [];
                return $wrap('prediction', [
                    'dropout_risk' => $r['dropout_risk'] ?? $r['risk'] ?? 'Unknown',
                    'risk_probability' => isset($r['risk_probability']) ? (float)$r['risk_probability'] : (isset($r['probability']) ? (float)$r['probability'] : 0.0),
                    'intervention_urgency' => $r['intervention_urgency'] ?? $r['urgency'] ?? 'Unknown',
                    'confidence' => isset($r['confidence']) ? (float)$r['confidence'] : 0.0,
                    'risk_factors' => $r['risk_factors'] ?? $r['factors'] ?? [],
                ]);

            case 'predictive':
                $r = is_array($result) ? $result : [];
                $predData = $r['prediction'] ?? $r;
                return $wrap('prediction', [
                    'current_performance' => $predData['current_performance'] ?? 'Unknown',
                    'trend' => $predData['trend'] ?? 'Stable',
                    'confidence' => isset($predData['confidence']) ? (float)$predData['confidence'] : 0.5,
                    'forecasted' => $predData['forecasted'] ?? $predData['forecasted_satisfaction'] ?? [],
                ]);

            case 'risk_assessment':
                $r = is_array($result) ? $result : [];
                return $wrap('assessment', [
                    'overall_risk_score' => isset($r['overall_risk_score']) ? (float)$r['overall_risk_score'] : (isset($r['risk_score']) ? (float)$r['risk_score'] : 0.0),
                    'risk_level' => $r['risk_level'] ?? 'Unknown',
                    'risk_category' => $r['risk_category'] ?? 'General',
                    'compliance_impact' => $r['compliance_impact'] ?? 'Unknown',
                    'confidence' => isset($r['confidence']) ? (float)$r['confidence'] : 0.0,
                    'risk_breakdown' => $r['risk_breakdown'] ?? $r['breakdown'] ?? [],
                ]);

            case 'trend_analysis':
                $r = is_array($result) ? $result : [];
                return $wrap('trend_prediction', [
                    'current_satisfaction' => isset($r['current_satisfaction']) ? (float)$r['current_satisfaction'] : 0.0,
                    'trend_direction' => $r['trend_direction'] ?? $r['trend'] ?? 'Stable',
                    'trend_strength' => $r['trend_strength'] ?? 'Moderate',
                    'forecasted_satisfaction' => $r['forecasted_satisfaction'] ?? $r['forecast'] ?? [],
                    'confidence' => isset($r['confidence']) ? (float)$r['confidence'] : 0.0,
                ]);

            case 'comprehensive':
                // Expect the flask service to return an object with named sub-results; pass through but wrap under analytics_results
                $r = is_array($result) ? $result : [];
                return $wrap('analytics_results', $r);

            default:
                // Unknown types: return raw under 'result'
                return $wrap('result', is_array($result) ? $result : ['value' => $result]);
        }
    }

    /**
     * Get empty result structure for each analysis type
     */
    protected function getEmptyResultStructure(string $type): array
    {
        switch ($type) {
            case 'compliance':
                return ['prediction' => [
                    'prediction' => 'Unknown',
                    'prediction_probability' => 0.0,
                    'weighted_score' => 0.0,
                    'risk_level' => 'Unknown',
                    'confidence' => 0.0
                ]];
            case 'sentiment':
                return ['sentiment_analysis' => []];
            case 'clustering':
                return ['clustering_result' => [
                    'clusters' => 0,
                    'detailed_clusters' => [],
                    'metrics' => [],
                    'insights' => [],
                    'total_samples' => 0
                ]];
            default:
                return ['result' => []];
        }
    }
}
