<?php

namespace App\Services;

use App\Models\SurveyResponse;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Clustering\KMeans;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AIService
{
    protected $flaskClient;
    protected $useFlaskService;

    public function __construct()
    {
        $this->flaskClient = app(FlaskAIClient::class);
        $this->useFlaskService = config('ai.fallback_to_php', true) ? $this->flaskClient->isServiceAvailable() : true;
    }
    /**
     * Predict ISO 21001 compliance level based on composite indices
     * Uses Flask AI service with PHP fallback
     */
    public function predictCompliance($responseData)
    {
        // Force array and debug data structure
        if (!is_array($responseData)) {
            $responseData = (array) $responseData;
        }

        // Try Flask service first if available
        if ($this->useFlaskService && config('ai.models.compliance_predictor.enabled', true)) {
            try {
                $flaskResult = $this->flaskClient->predictCompliance($responseData);

                if ($flaskResult && isset($flaskResult['success']) && $flaskResult['success']) {
                    Log::info('Flask AI service compliance prediction successful');
                    // Flask returns data under 'prediction' key, not 'data'
                    return $flaskResult['prediction'] ?? $flaskResult['data'] ?? $flaskResult;
                }
            } catch (\Exception $e) {
                Log::warning('Flask AI service compliance prediction failed, falling back to PHP', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Fallback to PHP implementation
        return $this->predictCompliancePHP($responseData);
    }

    /**
     * Original PHP implementation (fallback)
     */
    protected function predictCompliancePHP($responseData)
    {
        // Extract and explicitly cast to float to avoid type issues
        $learnerNeeds = isset($responseData['learner_needs_index']) ? (float) $responseData['learner_needs_index'] : 0.0;
        $satisfaction = isset($responseData['satisfaction_score']) ? (float) $responseData['satisfaction_score'] : 0.0;
        $success = isset($responseData['success_index']) ? (float) $responseData['success_index'] : 0.0;
        $safety = isset($responseData['safety_index']) ? (float) $responseData['safety_index'] : 0.0;
        $wellbeing = isset($responseData['wellbeing_index']) ? (float) $responseData['wellbeing_index'] : 0.0;
        $overall = isset($responseData['overall_satisfaction']) ? (float) $responseData['overall_satisfaction'] : 0.0;

        $indices = [
            'learner_needs_index' => $learnerNeeds,
            'satisfaction_score' => $satisfaction,
            'success_index' => $success,
            'safety_index' => $safety,
            'wellbeing_index' => $wellbeing,
            'overall_satisfaction' => $overall
        ];

        // Calculate weighted compliance score (ISO 21001 weighted metrics)
        $weights = config('ai.iso_21001.compliance_weights', [
            'learner_needs_index' => 0.15,
            'satisfaction_score' => 0.25,
            'success_index' => 0.20,
            'safety_index' => 0.20,
            'wellbeing_index' => 0.15,
            'overall_satisfaction' => 0.05
        ]);

        $weightedScore = (
            $indices['learner_needs_index'] * $weights['learner_needs_index'] +
            $indices['satisfaction_score'] * $weights['satisfaction_score'] +
            $indices['success_index'] * $weights['success_index'] +
            $indices['safety_index'] * $weights['safety_index'] +
            $indices['wellbeing_index'] * $weights['wellbeing_index'] +
            $indices['overall_satisfaction'] * $weights['overall_satisfaction']
        );

        // Determine compliance prediction based on weighted score
        $thresholds = config('ai.iso_21001.risk_thresholds', [
            'high_compliance' => 4.2,
            'moderate_compliance' => 3.5,
            'low_compliance' => 3.0
        ]);

        if ($weightedScore >= $thresholds['high_compliance']) {
            $prediction = 'High ISO 21001 Compliance';
            $riskLevel = 'Low';
            $confidence = 0.95;
        } elseif ($weightedScore >= $thresholds['moderate_compliance']) {
            $prediction = 'Moderate ISO 21001 Compliance';
            $riskLevel = 'Medium';
            $confidence = 0.75;
        } else {
            $prediction = 'Low ISO 21001 Compliance';
            $riskLevel = 'High';
            $confidence = 0.55;
        }

        // Adjust confidence based on data completeness and score consistency
        $scoreVariance = $this->calculateScoreVariance($indices);
        $confidence = max(0.5, $confidence - ($scoreVariance * 0.1));

        return [
            'prediction' => $prediction,
            'risk_level' => $riskLevel,
            'confidence' => round($confidence, 2),
            'weighted_score' => round($weightedScore, 2),
            'indices_used' => $indices,
            'model_used' => 'PHP Fallback (Rule-based)',
            'analysis' => [
                'score_variance' => round($scoreVariance, 2),
                'recommended_actions' => $this->generateRecommendations($weightedScore, $indices)
            ]
        ];
    }

    /**
     * Calculate variance across all indices to assess data consistency
     */
    private function calculateScoreVariance($indices)
    {
        $values = array_values($indices);
        $mean = array_sum($values) / count($values);
        $variance = 0;

        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }

        return $variance / count($values);
    }

    /**
     * Generate ISO 21001 specific recommendations based on compliance score
     */
    private function generateRecommendations($weightedScore, $indices)
    {
        $recommendations = [];

        if ($indices['safety_index'] < 3.5) {
            $recommendations[] = 'URGENT: Review and enhance safety protocols and emergency preparedness (ISO 21001:7.2)';
        }

        if ($indices['wellbeing_index'] < 3.5) {
            $recommendations[] = 'PRIORITY: Implement comprehensive wellbeing support programs (ISO 21001:7.3)';
        }

        if ($indices['satisfaction_score'] < 3.5) {
            $recommendations[] = 'IMMEDIATE: Address learner satisfaction issues through curriculum and teaching improvements (ISO 21001:7.1)';
        }

        if ($weightedScore < 3.5) {
            $recommendations[] = 'CRITICAL: Conduct full ISO 21001 compliance audit and develop improvement plan';
        }

        return $recommendations;
    }

    /**
     * Cluster survey responses using advanced ML algorithms
     * Uses Flask AI service with PHP fallback
     */
    public function clusterResponses($responses, $k = 3)
    {
        // Try Flask service first if available
        if ($this->useFlaskService && config('ai.models.student_clusterer.enabled', true)) {
            try {
                $responseData = $responses->map(function($response) {
                    return $response->toArray();
                })->toArray();

                $flaskResult = $this->flaskClient->clusterStudents($responseData, $k);

                if ($flaskResult && isset($flaskResult['success']) && $flaskResult['success']) {
                    Log::info('Flask AI service clustering successful');
                    // Flask returns data under 'clustering_result' key, not 'data'
                    return $flaskResult['clustering_result'] ?? $flaskResult['data'] ?? $flaskResult;
                }
            } catch (\Exception $e) {
                Log::warning('Flask AI service clustering failed, falling back to PHP', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Fallback to PHP implementation
        return $this->clusterResponsesPHP($responses, $k);
    }

    /**
     * Original PHP clustering implementation (fallback)
     */
    protected function clusterResponsesPHP($responses, $k = 3)
    {
        if ($responses->count() < $k) {
            return [
                'message' => 'Insufficient data for clustering',
                'clusters' => [],
                'error' => 'Need at least ' . $k . ' responses for ' . $k . ' clusters',
                'model_used' => 'PHP Fallback (K-Means)'
            ];
        }

        // Prepare feature vectors for clustering (normalized ratings)
        $samples = [];
        $labels = [];

        foreach ($responses as $index => $response) {
            $sample = [
                // Normalize ratings to 0-1 scale for clustering
                ($response->curriculum_relevance_rating ?? 0) / 5,
                ($response->learning_pace_appropriateness ?? 0) / 5,
                ($response->individual_support_availability ?? 0) / 5,
                ($response->learning_style_accommodation ?? 0) / 5,
                ($response->teaching_quality_rating ?? 0) / 5,
                ($response->learning_environment_rating ?? 0) / 5,
                ($response->overall_satisfaction ?? 0) / 5,
                ($response->grade_average ?? 0) / 4, // GPA scale 0-4
                ($response->attendance_rate ?? 0) / 100, // Percentage 0-1
            ];

            $samples[] = $sample;
            $labels[] = 'Response_' . $response->id;
        }

        try {
            $kmeans = new KMeans($k);
            $clusters = $kmeans->cluster($samples);

            // Group responses by cluster
            $clusterGroups = [];
            for ($i = 0; $i < $k; $i++) {
                $clusterGroups[] = [
                    'cluster_id' => $i + 1,
                    'size' => 0,
                    'average_satisfaction' => 0,
                    'average_performance' => 0,
                    'response_ids' => [],
                    'characteristics' => []
                ];
            }

            foreach ($clusters as $clusterId => $responseIndex) {
                $clusterGroups[$clusterId]['size']++;
                $clusterGroups[$clusterId]['response_ids'][] = $labels[$responseIndex];

                // Calculate cluster averages (simplified)
                $satisfaction = ($responses[$responseIndex]->overall_satisfaction ?? 0);
                $performance = ($responses[$responseIndex]->grade_average ?? 0);

                // This would need accumulation and averaging in full implementation
            }

            return [
                'message' => 'Clustering completed successfully',
                'num_clusters' => $k,
                'clusters' => $clusterGroups,
                'model_used' => 'PHP Fallback (K-Means)',
                'insights' => [
                    'clustering_algorithm' => 'K-Means',
                    'features_used' => count($samples[0]) . ' normalized metrics',
                    'recommendation' => 'Review clusters for targeted interventions based on similar learner profiles'
                ]
            ];

        } catch (\Exception $e) {
            return [
                'message' => 'Clustering failed',
                'error' => $e->getMessage(),
                'clusters' => [],
                'model_used' => 'PHP Fallback (Failed)'
            ];
        }
    }

    /**
     * Analyze sentiment of student feedback comments
     * Uses Flask AI service with PHP fallback
     */
    public function analyzeSentiment($comments)
    {
        // Try Flask service first if available
        if ($this->useFlaskService && config('ai.models.sentiment_analyzer.enabled', true)) {
            try {
                $flaskResult = $this->flaskClient->analyzeSentiment($comments);

                if ($flaskResult && isset($flaskResult['success']) && $flaskResult['success']) {
                    Log::info('Flask AI service sentiment analysis successful');
                    // Flask returns data under 'sentiment_analysis' key, not 'data'
                    return $flaskResult['sentiment_analysis'] ?? $flaskResult['data'] ?? $flaskResult;
                }
            } catch (\Exception $e) {
                Log::warning('Flask AI service sentiment analysis failed, falling back to PHP', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Fallback to PHP implementation
        return $this->analyzeSentimentPHP($comments);
    }

    /**
     * Original PHP sentiment analysis implementation (fallback)
     */
    protected function analyzeSentimentPHP($comments)
    {
        $positiveKeywords = ['great', 'excellent', 'love', 'amazing', 'wonderful', 'helpful', 'supportive', 'engaging', 'interesting', 'effective'];
        $negativeKeywords = ['poor', 'bad', 'terrible', 'awful', 'boring', 'unhelpful', 'inadequate', 'frustrating', 'stressful', 'overwhelming'];
        $neutralKeywords = ['okay', 'average', 'fine', 'neutral', 'satisfactory', 'acceptable'];

        $totalSentimentScore = 0;
        $totalWords = 0;
        $sentimentBreakdown = [
            'positive' => 0,
            'negative' => 0,
            'neutral' => 0
        ];

        foreach ($comments as $comment) {
            $wordCount = str_word_count(strtolower($comment));
            $totalWords += $wordCount;

            $positiveCount = 0;
            $negativeCount = 0;
            $neutralCount = 0;

            // Simple keyword matching (in production, use proper NLP)
            foreach ($positiveKeywords as $keyword) {
                $positiveCount += substr_count(strtolower($comment), $keyword);
            }
            foreach ($negativeKeywords as $keyword) {
                $negativeCount += substr_count(strtolower($comment), $keyword);
            }
            foreach ($neutralKeywords as $keyword) {
                $neutralCount += substr_count(strtolower($comment), $keyword);
            }

            // Calculate sentiment for this comment
            $commentSentiment = ($positiveCount - $negativeCount) / max(1, $wordCount);
            $totalSentimentScore += $commentSentiment;

            // Categorize comment
            if ($positiveCount > $negativeCount && $positiveCount > 0) {
                $sentimentBreakdown['positive']++;
            } elseif ($negativeCount > $positiveCount && $negativeCount > 0) {
                $sentimentBreakdown['negative']++;
            } else {
                $sentimentBreakdown['neutral']++;
            }
        }

        $overallSentiment = $totalWords > 0 ? $totalSentimentScore / count($comments) : 0;
        $sentimentPercentage = $totalWords > 0 ? ($overallSentiment + 1) * 50 : 50; // Scale to 0-100

        return [
            'overall_sentiment' => $overallSentiment >= 0 ? 'Positive' : ($overallSentiment <= -0.2 ? 'Negative' : 'Neutral'),
            'sentiment_score' => round($sentimentPercentage, 2), // 0-100 scale
            'breakdown' => $sentimentBreakdown,
            'total_comments_analyzed' => count($comments),
            'total_words_analyzed' => $totalWords,
            'model_used' => 'PHP Fallback (Keyword-based)',
            'iso_21001_insights' => [
                'learner_satisfaction_indicator' => $sentimentPercentage >= 70 ? 'High' : ($sentimentPercentage >= 50 ? 'Moderate' : 'Low'),
                'action_required' => $sentimentPercentage < 60,
                'recommendation' => $this->generateSentimentRecommendations($overallSentiment)
            ]
        ];
    }

    /**
     * Generate recommendations based on sentiment analysis results
     */
    private function generateSentimentRecommendations($sentimentScore)
    {
        if ($sentimentScore >= 0.1) {
            return 'Continue current practices and identify best practices for scaling';
        } elseif ($sentimentScore >= -0.1) {
            return 'Monitor sentiment trends and address emerging concerns proactively';
        } else {
            return 'URGENT: Implement immediate interventions to address negative learner experiences (ISO 21001:7.1.2)';
        }
    }

    /**
     * Predict student academic performance
     * Uses Flask AI service with PHP fallback
     */
    public function predictPerformance($responseData)
    {
        // Force array and debug data structure
        if (!is_array($responseData)) {
            $responseData = (array) $responseData;
        }

        // Try Flask service first if available
        if ($this->useFlaskService && config('ai.models.compliance_predictor.enabled', true)) {
            try {
                $flaskResult = $this->flaskClient->predictPerformance($responseData);

                if ($flaskResult && isset($flaskResult['success']) && $flaskResult['success']) {
                    Log::info('Flask AI service performance prediction successful');
                    // Flask returns data under 'prediction' key, not 'data'
                    return $flaskResult['prediction'] ?? $flaskResult['data'] ?? $flaskResult;
                }
            } catch (\Exception $e) {
                Log::warning('Flask AI service performance prediction failed, falling back to PHP', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Fallback to PHP implementation
        return $this->predictPerformancePHP($responseData);
    }

    /**
     * PHP fallback for performance prediction
     */
    protected function predictPerformancePHP($responseData)
    {
        // Extract key performance indicators
        $attendance = isset($responseData['attendance_rate']) ? (float) $responseData['attendance_rate'] / 100 : 0.8;
        $participation = isset($responseData['participation_score']) ? (float) $responseData['participation_score'] / 5 : 0.8;
        $satisfaction = isset($responseData['overall_satisfaction']) ? (float) $responseData['overall_satisfaction'] / 5 : 0.8;
        $progress = isset($responseData['academic_progress_rating']) ? (float) $responseData['academic_progress_rating'] / 5 : 0.8;

        // Weighted GPA prediction
        $predictedGPA = (
            $attendance * 0.3 +
            $participation * 0.25 +
            $satisfaction * 0.25 +
            $progress * 0.2
        ) * 4.0;

        $predictedGPA = max(0, min(4.0, $predictedGPA));

        // Determine performance level
        if ($predictedGPA >= 3.5) {
            $prediction = 'Excellent';
            $riskLevel = 'Low';
        } elseif ($predictedGPA >= 3.0) {
            $prediction = 'Good';
            $riskLevel = 'Low';
        } elseif ($predictedGPA >= 2.5) {
            $prediction = 'Satisfactory';
            $riskLevel = 'Medium';
        } elseif ($predictedGPA >= 2.0) {
            $prediction = 'Needs Improvement';
            $riskLevel = 'High';
        } else {
            $prediction = 'Critical';
            $riskLevel = 'High';
        }

        return [
            'prediction' => $prediction,
            'predicted_gpa' => round($predictedGPA, 2),
            'risk_level' => $riskLevel,
            'confidence' => 0.7,
            'model_used' => 'PHP Fallback (Weighted Average)',
            'recommendations' => $this->generatePerformanceRecommendations($predictedGPA, $responseData)
        ];
    }

    /**
     * Predict student dropout risk
     * Uses Flask AI service with PHP fallback
     */
    public function predictDropoutRisk($responseData)
    {
        // Force array and debug data structure
        if (!is_array($responseData)) {
            $responseData = (array) $responseData;
        }

        // Try Flask service first if available
        if ($this->useFlaskService && config('ai.models.compliance_predictor.enabled', true)) {
            try {
                $flaskResult = $this->flaskClient->predictDropoutRisk($responseData);

                if ($flaskResult && isset($flaskResult['success']) && $flaskResult['success']) {
                    Log::info('Flask AI service dropout risk prediction successful');
                    // Flask returns data under 'prediction' key, not 'data'
                    return $flaskResult['prediction'] ?? $flaskResult['data'] ?? $flaskResult;
                }
            } catch (\Exception $e) {
                Log::warning('Flask AI service dropout risk prediction failed, falling back to PHP', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Fallback to PHP implementation
        return $this->predictDropoutRiskPHP($responseData);
    }

    /**
     * PHP fallback for dropout risk prediction
     */
    protected function predictDropoutRiskPHP($responseData)
    {
        $riskScore = 0;
        $riskFactors = [];

        // Attendance factor
        $attendance = isset($responseData['attendance_rate']) ? (float) $responseData['attendance_rate'] : 80;
        if ($attendance < 75) {
            $riskScore += 0.3;
            $riskFactors[] = 'Low attendance rate';
        }

        // Satisfaction factor
        $satisfaction = isset($responseData['overall_satisfaction']) ? (float) $responseData['overall_satisfaction'] : 3;
        if ($satisfaction < 3) {
            $riskScore += 0.25;
            $riskFactors[] = 'Low satisfaction';
        }

        // Academic progress factor
        $progress = isset($responseData['academic_progress_rating']) ? (float) $responseData['academic_progress_rating'] : 3;
        if ($progress < 3) {
            $riskScore += 0.2;
            $riskFactors[] = 'Poor academic progress';
        }

        // Safety factors
        $safety = isset($responseData['physical_safety_rating']) ? (float) $responseData['physical_safety_rating'] : 4;
        $psychSafety = isset($responseData['psychological_safety_rating']) ? (float) $responseData['psychological_safety_rating'] : 4;
        if ($safety < 3 || $psychSafety < 3) {
            $riskScore += 0.15;
            $riskFactors[] = 'Safety concerns';
        }

        // Participation factor
        $participation = isset($responseData['participation_score']) ? (float) $responseData['participation_score'] : 3;
        if ($participation < 3) {
            $riskScore += 0.1;
            $riskFactors[] = 'Low participation';
        }

        $riskProbability = min($riskScore, 1.0);

        if ($riskProbability >= 0.8) {
            $riskLevel = 'Very High Risk';
            $urgency = 'Immediate';
        } elseif ($riskProbability >= 0.6) {
            $riskLevel = 'High Risk';
            $urgency = 'Urgent';
        } elseif ($riskProbability >= 0.4) {
            $riskLevel = 'Moderate Risk';
            $urgency = 'Priority';
        } elseif ($riskProbability >= 0.2) {
            $riskLevel = 'Low Risk';
            $urgency = 'Monitor';
        } else {
            $riskLevel = 'Very Low Risk';
            $urgency = 'Routine';
        }

        return [
            'dropout_risk' => $riskLevel,
            'risk_probability' => round($riskProbability, 4),
            'intervention_urgency' => $urgency,
            'confidence' => 0.65,
            'model_used' => 'PHP Fallback (Rule-based)',
            'risk_factors' => $riskFactors,
            'recommendations' => $this->generateDropoutRecommendations($riskProbability, $responseData)
        ];
    }

    /**
     * Comprehensive risk assessment
     * Uses Flask AI service with PHP fallback
     */
    public function assessRisk($responseData)
    {
        // Force array and debug data structure
        if (!is_array($responseData)) {
            $responseData = (array) $responseData;
        }

        // Try Flask service first if available
        if ($this->useFlaskService && config('ai.models.compliance_predictor.enabled', true)) {
            try {
                $flaskResult = $this->flaskClient->assessRisk($responseData);

                if ($flaskResult && isset($flaskResult['success']) && $flaskResult['success']) {
                    Log::info('Flask AI service risk assessment successful');
                    // Flask returns data under 'assessment' key, not 'data'
                    return $flaskResult['assessment'] ?? $flaskResult['data'] ?? $flaskResult;
                }
            } catch (\Exception $e) {
                Log::warning('Flask AI service risk assessment failed, falling back to PHP', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Fallback to PHP implementation
        return $this->assessRiskPHP($responseData);
    }

    /**
     * PHP fallback for comprehensive risk assessment
     */
    protected function assessRiskPHP($responseData)
    {
        $riskComponents = [
            'academic' => 0,
            'engagement' => 0,
            'safety' => 0,
            'wellbeing' => 0,
            'satisfaction' => 0
        ];

        // Calculate component risks
        $academicFactors = [
            isset($responseData['academic_progress_rating']) ? (float) $responseData['academic_progress_rating'] : 3,
            isset($responseData['skill_development_rating']) ? (float) $responseData['skill_development_rating'] : 3,
            isset($responseData['grade_average']) ? (float) $responseData['grade_average'] * 1.25 : 2.5
        ];
        $riskComponents['academic'] = max(0, (5 - (array_sum($academicFactors) / count($academicFactors))) / 5) * 100;

        $engagementFactors = [
            isset($responseData['attendance_rate']) ? (float) $responseData['attendance_rate'] / 20 : 4,
            isset($responseData['participation_score']) ? (float) $responseData['participation_score'] : 3,
            isset($responseData['peer_interaction_satisfaction']) ? (float) $responseData['peer_interaction_satisfaction'] : 3
        ];
        $riskComponents['engagement'] = max(0, (5 - (array_sum($engagementFactors) / count($engagementFactors))) / 5) * 100;

        $safetyFactors = [
            isset($responseData['physical_safety_rating']) ? (float) $responseData['physical_safety_rating'] : 4,
            isset($responseData['psychological_safety_rating']) ? (float) $responseData['psychological_safety_rating'] : 4,
            isset($responseData['emergency_preparedness_rating']) ? (float) $responseData['emergency_preparedness_rating'] : 4
        ];
        $riskComponents['safety'] = max(0, (5 - (array_sum($safetyFactors) / count($safetyFactors))) / 5) * 100;

        $wellbeingFactors = [
            isset($responseData['mental_health_support_rating']) ? (float) $responseData['mental_health_support_rating'] : 4,
            isset($responseData['stress_management_support']) ? (float) $responseData['stress_management_support'] : 4,
            isset($responseData['overall_wellbeing_rating']) ? (float) $responseData['overall_wellbeing_rating'] : 4
        ];
        $riskComponents['wellbeing'] = max(0, (5 - (array_sum($wellbeingFactors) / count($wellbeingFactors))) / 5) * 100;

        $riskComponents['satisfaction'] = max(0, (5 - (isset($responseData['overall_satisfaction']) ? (float) $responseData['overall_satisfaction'] : 3)) / 5) * 100;

        // Calculate overall risk
        $weights = ['academic' => 0.3, 'engagement' => 0.25, 'safety' => 0.2, 'wellbeing' => 0.15, 'satisfaction' => 0.1];
        $overallRisk = 0;
        foreach ($riskComponents as $component => $score) {
            $overallRisk += $score * $weights[$component];
        }

        if ($overallRisk >= 75) {
            $riskLevel = 'Critical';
            $category = 'High Risk';
            $impact = 'Severe';
        } elseif ($overallRisk >= 60) {
            $riskLevel = 'High';
            $category = 'High Risk';
            $impact = 'Significant';
        } elseif ($overallRisk >= 40) {
            $riskLevel = 'Moderate';
            $category = 'Medium Risk';
            $impact = 'Moderate';
        } elseif ($overallRisk >= 25) {
            $riskLevel = 'Low';
            $category = 'Low Risk';
            $impact = 'Minor';
        } else {
            $riskLevel = 'Minimal';
            $category = 'Very Low Risk';
            $impact = 'Negligible';
        }

        return [
            'overall_risk_score' => round($overallRisk, 2),
            'risk_level' => $riskLevel,
            'risk_category' => $category,
            'compliance_impact' => $impact,
            'confidence' => 0.7,
            'model_used' => 'PHP Fallback (Composite Scoring)',
            'risk_breakdown' => $riskComponents,
            'action_plan' => $this->generateRiskActionPlan($overallRisk, $riskComponents)
        ];
    }

    /**
     * Predict satisfaction trends
     * Uses Flask AI service with PHP fallback
     */
    public function predictSatisfactionTrend($responseData)
    {
        // Force array and debug data structure
        if (!is_array($responseData)) {
            $responseData = (array) $responseData;
        }

        // Try Flask service first if available
        if ($this->useFlaskService && config('ai.models.compliance_predictor.enabled', true)) {
            try {
                $flaskResult = $this->flaskClient->predictSatisfactionTrend($responseData);

                if ($flaskResult && isset($flaskResult['success']) && $flaskResult['success']) {
                    Log::info('Flask AI service satisfaction trend prediction successful');
                    // Flask returns data under 'trend_prediction' key, not 'data'
                    return $flaskResult['trend_prediction'] ?? $flaskResult['data'] ?? $flaskResult;
                }
            } catch (\Exception $e) {
                Log::warning('Flask AI service satisfaction trend prediction failed, falling back to PHP', [
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Fallback to PHP implementation
        return $this->predictSatisfactionTrendPHP($responseData);
    }

    /**
     * PHP fallback for satisfaction trend prediction
     */
    protected function predictSatisfactionTrendPHP($responseData)
    {
        $currentSatisfaction = isset($responseData['overall_satisfaction']) ? (float) $responseData['overall_satisfaction'] : 3.0;

        // Simple trend analysis based on current satisfaction
        if ($currentSatisfaction > 4.0) {
            $direction = 'stable';
            $strength = 'moderate';
            $forecast = [4.2, 4.1, 4.0];
        } elseif ($currentSatisfaction > 3.5) {
            $direction = 'stable';
            $strength = 'weak';
            $forecast = [3.8, 3.7, 3.6];
        } elseif ($currentSatisfaction > 3.0) {
            $direction = 'improving';
            $strength = 'weak';
            $forecast = [3.2, 3.4, 3.5];
        } elseif ($currentSatisfaction > 2.5) {
            $direction = 'improving';
            $strength = 'moderate';
            $forecast = [2.8, 3.1, 3.3];
        } else {
            $direction = 'declining';
            $strength = 'strong';
            $forecast = [2.3, 2.1, 2.0];
        }

        return [
            'current_satisfaction' => round($currentSatisfaction, 2),
            'trend_direction' => $direction,
            'trend_strength' => $strength,
            'forecasted_satisfaction' => $forecast,
            'confidence' => 0.6,
            'model_used' => 'PHP Fallback (Rule-based)',
            'trend_analysis' => [
                'direction' => $direction,
                'strength' => $strength,
                'change_rate' => 0.0,
                'volatility' => 0.5
            ],
            'recommendations' => $this->generateTrendRecommendations($currentSatisfaction, $direction)
        ];
    }

    /**
     * Generate performance recommendations
     */
    private function generatePerformanceRecommendations($gpa, $data)
    {
        $recommendations = [];

        if ($gpa < 2.5) {
            $recommendations[] = 'URGENT: Implement intensive academic support program';
            $recommendations[] = 'Schedule tutoring and mentoring sessions';
        }

        if (isset($data['attendance_rate']) && $data['attendance_rate'] < 75) {
            $recommendations[] = 'Address attendance issues through counseling';
        }

        if (isset($data['participation_score']) && $data['participation_score'] < 3) {
            $recommendations[] = 'Increase student engagement activities';
        }

        return $recommendations;
    }

    /**
     * Generate dropout risk recommendations
     */
    private function generateDropoutRecommendations($risk, $data)
    {
        $recommendations = [];

        if ($risk >= 0.6) {
            $recommendations[] = 'URGENT: Immediate counseling and support intervention required';
        } elseif ($risk >= 0.4) {
            $recommendations[] = 'HIGH PRIORITY: Schedule regular check-ins and support meetings';
        }

        if (isset($data['attendance_rate']) && $data['attendance_rate'] < 75) {
            $recommendations[] = 'Address attendance barriers and provide transportation support';
        }

        return $recommendations;
    }

    /**
     * Generate risk action plan
     */
    private function generateRiskActionPlan($overallRisk, $components)
    {
        $actions = [];

        if ($overallRisk >= 75) {
            $actions['immediate'] = ['Conduct comprehensive risk audit', 'Implement emergency intervention protocols'];
        } elseif ($overallRisk >= 60) {
            $actions['urgent'] = ['Develop targeted improvement plans', 'Increase monitoring frequency'];
        } elseif ($overallRisk >= 40) {
            $actions['priority'] = ['Review policies and procedures', 'Enhance staff training'];
        }

        return $actions;
    }

    /**
     * Generate trend recommendations
     */
    private function generateTrendRecommendations($satisfaction, $direction)
    {
        $recommendations = [];

        if ($direction === 'declining') {
            $recommendations[] = 'PRIORITY: Identify causes of satisfaction decline';
            $recommendations[] = 'Implement immediate improvement measures';
        } elseif ($direction === 'improving') {
            $recommendations[] = 'Continue successful practices';
            $recommendations[] = 'Monitor for sustainability';
        }

        return $recommendations;
    }

    /**
     * Extract key themes and keywords from student feedback
     * Uses TF-IDF for important term identification
     */
    public function extractKeywords($comments, $minFrequency = 2)
    {
        if (empty($comments)) {
            return [
                'message' => 'No comments available for keyword extraction',
                'keywords' => [],
                'error' => 'Insufficient text data'
            ];
        }

        try {
            $tokenizer = new WhitespaceTokenizer();
            $transformer = new TfIdfTransformer();

            // Extract documents and tokens
            $documents = [];
            $allTokens = [];

            foreach ($comments as $comment) {
                $tokens = $tokenizer->tokenize(strtolower($comment));
                $documents[] = $tokens;
                $allTokens = array_merge($allTokens, $tokens);
            }

            // Remove stopwords and filter
            $stopwords = ['the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'is', 'are', 'was', 'were', 'i', 'you', 'he', 'she', 'it', 'we', 'they', 'a', 'an'];
            $filteredTokens = array_filter($allTokens, function($token) use ($stopwords) {
                return strlen($token) > 2 && !in_array($token, $stopwords) && ctype_alpha($token);
            });

            // Count term frequencies
            $termFrequencies = array_count_values($filteredTokens);
            arsort($termFrequencies);

            // Apply TF-IDF transformation (simplified)
            $transformer->fit($documents);
            $tfidfMatrix = $transformer->transform($documents);
            $tfidfScores = $tfidfMatrix;

            // Extract top keywords
            $topKeywords = [];
            foreach ($termFrequencies as $term => $frequency) {
                if ($frequency >= $minFrequency) {
                    $topKeywords[] = [
                        'keyword' => $term,
                        'frequency' => $frequency,
                        'tfidf_score' => isset($tfidfScores[0][$term]) ? round($tfidfScores[0][$term], 4) : 0,
                        'relevance' => $frequency * (isset($tfidfScores[0][$term]) ? $tfidfScores[0][$term] : 0)
                    ];
                }
            }

            // Sort by relevance
            usort($topKeywords, function($a, $b) {
                return $b['relevance'] <=> $a['relevance'];
            });

            return [
                'message' => 'Keyword extraction completed successfully',
                'total_keywords_extracted' => count($topKeywords),
                'top_keywords' => array_slice($topKeywords, 0, 20), // Top 20 keywords
                'all_keywords' => $topKeywords,
                'analysis' => [
                    'total_comments' => count($comments),
                    'total_unique_terms' => count($termFrequencies),
                    'terms_meeting_frequency_threshold' => count(array_filter($termFrequencies, function($freq) use ($minFrequency) { return $freq >= $minFrequency; })),
                    'iso_21001_recommendation' => 'Use extracted keywords to identify key themes for curriculum and support service improvements'
                ]
            ];

        } catch (\Exception $e) {
            return [
                'message' => 'Keyword extraction failed',
                'error' => $e->getMessage(),
                'keywords' => []
            ];
        }
    }
}
