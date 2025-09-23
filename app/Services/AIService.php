<?php

namespace App\Services;

use App\Models\SurveyResponse;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Clustering\KMeans;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Predict ISO 21001 compliance level based on composite indices
     * Uses weighted scoring system aligned with ISO 21001 requirements
     */
    public function predictCompliance($responseData)
    {
        // Force array and debug data structure
        if (!is_array($responseData)) {
            $responseData = (array) $responseData;
        }

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

        // Debug output via error log since Log facade import issue
        error_log('AIService Input Debug - Original data keys: ' . print_r(array_keys($responseData), true));
        error_log('AIService Input Debug - Extracted indices: ' . print_r($indices, true));

        // Calculate weighted compliance score (ISO 21001 weighted metrics)
        // Weights: Satisfaction (25%), Success (20%), Safety (20%), Needs (15%), Wellbeing (15%), Overall (5%)
        $weightedScore = (
            $indices['learner_needs_index'] * 0.15 +
            $indices['satisfaction_score'] * 0.25 +
            $indices['success_index'] * 0.20 +
            $indices['safety_index'] * 0.20 +
            $indices['wellbeing_index'] * 0.15 +
            $indices['overall_satisfaction'] * 0.05
        );

        // Determine compliance prediction based on weighted score
        if ($weightedScore >= 4.2) {
            $prediction = 'High ISO 21001 Compliance';
            $riskLevel = 'Low';
            $confidence = 0.95;
        } elseif ($weightedScore >= 3.5) {
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
     * Cluster survey responses using K-Means for pattern identification
     * Groups similar learner experiences for targeted interventions
     */
    public function clusterResponses($responses, $k = 3)
    {
        if ($responses->count() < $k) {
            return [
                'message' => 'Insufficient data for clustering',
                'clusters' => [],
                'error' => 'Need at least ' . $k . ' responses for ' . $k . ' clusters'
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
                'clusters' => []
            ];
        }
    }

    /**
     * Analyze sentiment of student feedback comments
     * Uses simple keyword-based analysis for ISO 21001 sentiment tracking
     */
    public function analyzeSentiment($comments)
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
