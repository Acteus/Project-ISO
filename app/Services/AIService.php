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
        // Simple rule-based compliance prediction
        // In a real implementation, this would use a trained ML model
        $score = ($responseData['course_content_rating'] +
                 $responseData['facilities_rating'] +
                 $responseData['support_services_rating'] +
                 $responseData['overall_satisfaction']) / 4;

        if ($score >= 4.0) {
            return ['prediction' => 'High Compliance', 'confidence' => 0.9, 'risk_level' => 'Low'];
        } elseif ($score >= 3.0) {
            return ['prediction' => 'Medium Compliance', 'confidence' => 0.7, 'risk_level' => 'Medium'];
        } else {
            return ['prediction' => 'Low Compliance', 'confidence' => 0.8, 'risk_level' => 'High'];
        }
    }

    public function clusterResponses($responses, $k = 3)
    {
        if ($responses->count() < $k) {
            return ['error' => 'Not enough data for clustering'];
        }

        $data = [];
        foreach ($responses as $response) {
            $data[] = [
                $response->course_content_rating,
                $response->facilities_rating,
                $response->support_services_rating,
                $response->overall_satisfaction,
                $response->year_level
            ];
        }

        $kmeans = new KMeans($k);
        $clusters = $kmeans->cluster($data);

        $result = [];
        foreach ($clusters as $clusterId => $cluster) {
            $result["Cluster " . ($clusterId + 1)] = [
                'count' => count($cluster),
                'average_ratings' => $this->calculateClusterAverage($cluster, $data)
            ];
        }

        return $result;
    }

    public function analyzeSentiment($comments)
    {
        // Simple sentiment analysis based on keywords
        // In a real implementation, this would use NLP libraries
        $positiveWords = ['good', 'great', 'excellent', 'amazing', 'wonderful', 'best', 'love', 'happy'];
        $negativeWords = ['bad', 'poor', 'terrible', 'awful', 'worst', 'hate', 'sad', 'disappointed'];

        $positiveCount = 0;
        $negativeCount = 0;
        $totalWords = 0;

        foreach ($comments as $comment) {
            if (!$comment) continue;

            $words = explode(' ', strtolower($comment));
            $totalWords += count($words);

            foreach ($words as $word) {
                if (in_array($word, $positiveWords)) {
                    $positiveCount++;
                } elseif (in_array($word, $negativeWords)) {
                    $negativeCount++;
                }
            }
        }

        $totalSentimentWords = $positiveCount + $negativeCount;

        if ($totalSentimentWords === 0) {
            return ['sentiment' => 'Neutral', 'score' => 0];
        }

        $score = ($positiveCount - $negativeCount) / $totalSentimentWords;

        if ($score > 0.1) {
            return ['sentiment' => 'Positive', 'score' => $score];
        } elseif ($score < -0.1) {
            return ['sentiment' => 'Negative', 'score' => $score];
        } else {
            return ['sentiment' => 'Neutral', 'score' => $score];
        }
    }

    public function extractKeywords($comments, $minFrequency = 2)
    {
        $allWords = [];
        $stopWords = ['the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'an', 'a'];

        foreach ($comments as $comment) {
            if (!$comment) continue;

            $words = explode(' ', strtolower($comment));
            foreach ($words as $word) {
                $word = preg_replace('/[^\w]/', '', $word);
                if (strlen($word) > 2 && !in_array($word, $stopWords)) {
                    $allWords[] = $word;
                }
            }
        }

        $wordCount = array_count_values($allWords);
        $keywords = array_filter($wordCount, function($count) use ($minFrequency) {
            return $count >= $minFrequency;
        });

        arsort($keywords);
        return array_slice($keywords, 0, 10, true);
    }

    private function calculateClusterAverage($clusterIndices, $data)
    {
        $averages = [0, 0, 0, 0, 0];
        foreach ($clusterIndices as $index) {
            for ($i = 0; $i < 5; $i++) {
                $averages[$i] += $data[$index][$i];
            }
        }

        $count = count($clusterIndices);
        return [
            'course_content' => round($averages[0] / $count, 2),
            'facilities' => round($averages[1] / $count, 2),
            'support_services' => round($averages[2] / $count, 2),
            'overall_satisfaction' => round($averages[3] / $count, 2),
            'year_level' => round($averages[4] / $count, 2),
        ];
    }
}
