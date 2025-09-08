<?php

namespace App\Services;

use App\Models\SurveyResponse;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Clustering\KMeans;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Tokenization\WhitespaceTokenizer;

class AIService
{
    public function predictCompliance($responseData)
    {
        // ISO 21001 Weighted Compliance Prediction
        $indices = [
            'learner_needs_index' => $responseData['learner_needs_index'],
            'satisfaction_score' => $responseData['satisfaction_score'],
            'success_index' => $responseData['success_index'],
            'safety_index' => $responseData['safety_index'],
            'wellbeing_index' => $responseData['wellbeing_index'],
            'overall_satisfaction' => $responseData['overall_satisfaction']
        ];

        // Weighted compliance score based on ISO 21001 priorities
        $weightedScore = (
            $indices['learner_needs_index'] * 0.15 +
            $indices['satisfaction_score'] * 0.25 +
            $indices['success_index'] * 0.20 +
            $indices['safety_index'] * 0.20 +
            $indices['wellbeing_index'] * 0.15 +
            $indices['overall_satisfaction'] * 0.05
        );

        $confidence = min(0.95, 0.6 + ($weightedScore / 5) * 0.35); // Higher scores = higher confidence

        if ($weightedScore >= 4.0) {
            return [
                'prediction' => 'High ISO 21001 Compliance',
                'confidence' => round($confidence, 2),
                'risk_level' => 'Low',
                'weighted_score' => round($weightedScore, 2),
                'recommendations' => ['Maintain current practices', 'Continue monitoring key indicators']
            ];
        } elseif ($weightedScore >= 3.0) {
            return [
                'prediction' => 'Medium ISO 21001 Compliance',
                'confidence' => round($confidence, 2),
                'risk_level' => 'Medium',
                'weighted_score' => round($weightedScore, 2),
                'recommendations' => [
                    'Review low-performing indices',
                    'Implement targeted improvement plans',
                    'Conduct stakeholder feedback sessions'
                ]
            ];
        } else {
            return [
                'prediction' => 'Low ISO 21001 Compliance',
                'confidence' => round($confidence, 2),
                'risk_level' => 'High',
                'weighted_score' => round($weightedScore, 2),
                'recommendations' => [
                    'Immediate action required on critical indices',
                    'Conduct comprehensive compliance audit',
                    'Develop urgent improvement action plan',
                    'Engage external ISO 21001 consultants'
                ]
            ];
        }
    }

    public function clusterResponses($responses, $k = 3)
    {
        if ($responses->count() < $k) {
            return ['error' => 'Not enough data for clustering'];
        }

        $data = [];
        foreach ($responses as $response) {
            // Use ISO 21001 indices and indirect metrics for clustering
            $data[] = [
                // Composite indices (normalized 0-1 scale)
                round(($response->curriculum_relevance_rating + $response->learning_pace_appropriateness +
                       $response->individual_support_availability + $response->learning_style_accommodation) / 20, 2),
                round(($response->teaching_quality_rating + $response->learning_environment_rating +
                       $response->peer_interaction_satisfaction + $response->extracurricular_satisfaction) / 20, 2),
                round(($response->academic_progress_rating + $response->skill_development_rating +
                       $response->critical_thinking_improvement + $response->problem_solving_confidence) / 20, 2),
                round(($response->physical_safety_rating + $response->psychological_safety_rating +
                       $response->bullying_prevention_effectiveness + $response->emergency_preparedness_rating) / 20, 2),
                round(($response->mental_health_support_rating + $response->stress_management_support +
                       $response->physical_health_support + $response->overall_wellbeing_rating) / 20, 2),
                // Indirect metrics (normalized)
                ($response->grade_average ?? 0) / 4.0,
                ($response->attendance_rate ?? 0) / 100,
                ($response->participation_score ?? 0) / 100,
                $response->grade_level / 12, // Normalize grade level
            ];
        }

        $kmeans = new KMeans($k);
        $clusters = $kmeans->cluster($data);

        $result = [];
        foreach ($clusters as $clusterId => $cluster) {
            $result["Cluster " . ($clusterId + 1)] = [
                'count' => count($cluster),
                'learner_profile' => $this->calculateClusterProfile($cluster, $data, $responses),
                'recommendations' => $this->generateClusterRecommendations($cluster, $data, $responses)
            ];
        }

        return $result;
    }

    public function analyzeSentiment($comments)
    {
        // Enhanced sentiment analysis for ISO 21001 feedback
        // Categorize sentiment by feedback type and overall
        $positiveWords = ['good', 'great', 'excellent', 'amazing', 'wonderful', 'best', 'love', 'happy', 'helpful', 'supportive', 'safe', 'well', 'confident', 'progress', 'developed', 'improved', 'satisfied'];
        $negativeWords = ['bad', 'poor', 'terrible', 'awful', 'worst', 'hate', 'sad', 'disappointed', 'difficult', 'unhelpful', 'unsafe', 'stressed', 'anxious', 'struggling', 'declined', 'unsatisfied', 'bullying', 'pressure'];
        $neutralWords = ['okay', 'average', 'normal', 'fine', 'adequate', 'sufficient'];

        $overallPositive = 0;
        $overallNegative = 0;
        $overallNeutral = 0;
        $totalWords = 0;

        foreach ($comments as $comment) {
            if (!$comment) continue;

            $words = explode(' ', strtolower($comment));
            $totalWords += count($words);

            foreach ($words as $word) {
                $word = preg_replace('/[^\w]/', '', $word);
                if (strlen($word) < 3) continue;

                if (in_array($word, $positiveWords)) {
                    $overallPositive++;
                } elseif (in_array($word, $negativeWords)) {
                    $overallNegative++;
                } elseif (in_array($word, $neutralWords)) {
                    $overallNeutral++;
                }
            }
        }

        $totalSentimentWords = $overallPositive + $overallNegative + $overallNeutral;

        if ($totalSentimentWords === 0) {
            return [
                'overall_sentiment' => 'Neutral',
                'overall_score' => 0,
                'word_counts' => ['positive' => 0, 'negative' => 0, 'neutral' => 0],
                'analysis' => 'No sentiment-bearing words detected'
            ];
        }

        $overallScore = (($overallPositive - $overallNegative) / $totalSentimentWords) * 100;

        $sentiment = 'Neutral';
        if ($overallScore > 20) {
            $sentiment = 'Positive';
        } elseif ($overallScore < -20) {
            $sentiment = 'Negative';
        }

        return [
            'overall_sentiment' => $sentiment,
            'overall_score' => round($overallScore, 2),
            'word_counts' => [
                'positive' => $overallPositive,
                'negative' => $overallNegative,
                'neutral' => $overallNeutral
            ],
            'analysis' => "Based on {$totalSentimentWords} sentiment words from learner feedback",
            'recommendations' => $this->generateSentimentRecommendations($sentiment, $overallScore)
        ];
    }

    public function extractKeywords($comments, $minFrequency = 2)
    {
        $allWords = [];
        $stopWords = ['the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'an', 'a', 'is', 'are', 'was', 'were', 'be', 'been', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might', 'must', 'can', 'shall'];

        // Categorize keywords by theme
        $themes = [
            'curriculum' => ['curriculum', 'course', 'subject', 'material', 'content', 'lesson', 'topic'],
            'teaching' => ['teacher', 'instructor', 'professor', 'teach', 'explain', 'method', 'style'],
            'environment' => ['classroom', 'facility', 'lab', 'equipment', 'resource', 'technology'],
            'support' => ['support', 'help', 'assistance', 'counseling', 'guidance', 'mentor'],
            'safety' => ['safety', 'secure', 'bullying', 'harassment', 'emergency', 'protection'],
            'wellbeing' => ['stress', 'mental', 'health', 'wellbeing', 'anxiety', 'pressure', 'balance']
        ];

        $themedWordCounts = array_fill_keys(array_keys($themes), []);

        foreach ($comments as $comment) {
            if (!$comment) continue;

            $words = explode(' ', strtolower($comment));
            foreach ($words as $word) {
                $cleanWord = preg_replace('/[^\w]/', '', $word);
                if (strlen($cleanWord) > 2 && !in_array($cleanWord, $stopWords)) {
                    $allWords[] = $cleanWord;

                    // Categorize words
                    foreach ($themes as $theme => $themeWords) {
                        if (in_array($cleanWord, $themeWords)) {
                            $themedWordCounts[$theme][] = $cleanWord;
                            break;
                        }
                    }
                }
            }
        }

        $wordCount = array_count_values($allWords);
        $keywords = array_filter($wordCount, function($count) use ($minFrequency) {
            return $count >= $minFrequency;
        });

        // Calculate theme frequencies
        $themeFrequencies = [];
        foreach ($themedWordCounts as $theme => $themeWords) {
            $themeCount = array_count_values($themeWords);
            $totalThemeWords = array_sum($themeCount);
            $themeFrequencies[$theme] = [
                'frequency' => $totalThemeWords,
                'top_keywords' => array_slice(array_slice($themeCount, 0, 3, true), 0, 3, true)
            ];
        }

        // Sort themes by frequency
        arsort($themeFrequencies);

        return [
            'overall_keywords' => array_slice($keywords, 0, 10, true),
            'themed_analysis' => $themeFrequencies,
            'total_words_analyzed' => count($allWords),
            'insights' => $this->generateKeywordInsights($themeFrequencies)
        ];
    }

    private function calculateClusterProfile($clusterIndices, $data, $responses)
    {
        $profileAverages = array_fill(0, count($data[0]), 0);

        $correspondingResponses = [];
        foreach ($clusterIndices as $index) {
            $correspondingResponses[] = $responses[$index];

            for ($i = 0; $i < count($data[0]); $i++) {
                $profileAverages[$i] += $data[$index][$i];
            }
        }

        $count = count($clusterIndices);
        $profile = [];
        $fieldMapping = [
            0 => 'learner_needs_index',
            1 => 'satisfaction_score',
            2 => 'success_index',
            3 => 'safety_index',
            4 => 'wellbeing_index',
            5 => 'grade_average_normalized',
            6 => 'attendance_rate_normalized',
            7 => 'participation_score_normalized',
            8 => 'grade_level_normalized'
        ];

        foreach ($profileAverages as $index => $total) {
            $profile[$fieldMapping[$index]] = round($total / $count, 3);
        }

        // Calculate demographic profile using array methods
        $gradeLevels = array_map(function($resp) { return $resp['grade_level']; }, $correspondingResponses);
        $gpaValues = array_map(function($resp) { return $resp['grade_average']; }, $correspondingResponses);
        $attendanceValues = array_map(function($resp) { return $resp['attendance_rate']; }, $correspondingResponses);
        $counselingValues = array_map(function($resp) { return $resp['counseling_sessions']; }, $correspondingResponses);
        $semesters = array_map(function($resp) { return $resp['semester']; }, $correspondingResponses);

        $demographics = [
            'average_age_equivalent' => count($gradeLevels) > 0 ? round(array_sum($gradeLevels) / count($gradeLevels), 1) : 0,
            'average_gpa' => count(array_filter($gpaValues)) > 0 ? round(array_sum(array_filter($gpaValues)) / count(array_filter($gpaValues)), 2) : 0,
            'average_attendance' => count(array_filter($attendanceValues)) > 0 ? round(array_sum(array_filter($attendanceValues)) / count(array_filter($attendanceValues)), 1) : 0,
            'total_counseling_sessions' => array_sum(array_filter($counselingValues)),
            'semester_distribution' => array_count_values($semesters)
        ];

        return [
            'iso_21001_profile' => $profile,
            'demographic_profile' => $demographics,
            'sample_size' => $count
        ];
    }

    private function generateClusterRecommendations($cluster, $data, $responses)
    {
        $profile = $this->calculateClusterProfile($cluster, $data, $responses);

        $recommendations = [];
        $criticalThreshold = 0.3; // Below 30% normalized score is concerning

        if ($profile['iso_21001_profile']['safety_index'] < $criticalThreshold) {
            $recommendations[] = 'Immediate attention needed for learner safety concerns';
        }

        if ($profile['iso_21001_profile']['wellbeing_index'] < $criticalThreshold) {
            $recommendations[] = 'Enhanced mental health and wellbeing support required';
        }

        if ($profile['demographic_profile']['average_attendance'] < 80) {
            $recommendations[] = 'Attendance improvement strategies needed for this learner group';
        }

        if ($profile['demographic_profile']['total_counseling_sessions'] == 0) {
            $recommendations[] = 'No counseling engagement detected - consider outreach programs';
        }

        if (empty($recommendations)) {
            $recommendations[] = 'Learner group appears stable - continue monitoring key indicators';
        }

        return $recommendations;
    }

    private function generateSentimentRecommendations($sentiment, $score)
    {
        if ($sentiment === 'Positive' && $score > 60) {
            return ['Continue current practices that are generating positive learner feedback'];
        } elseif ($sentiment === 'Positive' && $score > 30) {
            return ['Maintain positive momentum while addressing areas for continuous improvement'];
        } elseif ($sentiment === 'Neutral') {
            return ['Opportunity to enhance learner engagement and satisfaction through targeted initiatives'];
        } elseif ($sentiment === 'Negative' && $score > -60) {
            return ['Address specific learner concerns through focused improvement actions'];
        } else {
            return ['Urgent attention required to address significant learner dissatisfaction'];
        }
    }

    private function generateKeywordInsights($themeFrequencies)
    {
        $insights = [];

        if (isset($themeFrequencies['safety']) && $themeFrequencies['safety']['frequency'] > 5) {
            $insights[] = 'Safety concerns are prominent in learner feedback';
        }

        if (isset($themeFrequencies['wellbeing']) && $themeFrequencies['wellbeing']['frequency'] > 5) {
            $insights[] = 'Wellbeing and mental health are significant themes in feedback';
        }

        if (isset($themeFrequencies['teaching']) && $themeFrequencies['teaching']['frequency'] > 10) {
            $insights[] = 'Teaching quality is a major focus area for learners';
        }

        return $insights;
    }
}
