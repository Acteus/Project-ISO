<?php

namespace App\Services;

use App\Models\SurveyResponse;

class VisualizationService
{
    public function generateBarChartData($program = null, $yearLevel = null)
    {
        $query = SurveyResponse::query();

        if ($program) {
            $query->where('program', $program);
        }

        if ($yearLevel) {
            $query->where('year_level', $yearLevel);
        }

        $responses = $query->get();

        $categories = ['Course Content', 'Facilities', 'Support Services', 'Overall Satisfaction'];
        $data = [
            'labels' => $categories,
            'datasets' => [
                [
                    'label' => 'Average Rating',
                    'data' => [
                        round($responses->avg('course_content_rating'), 2),
                        round($responses->avg('facilities_rating'), 2),
                        round($responses->avg('support_services_rating'), 2),
                        round($responses->avg('overall_satisfaction'), 2),
                    ],
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ]
            ]
        ];

        return $data;
    }

    public function generatePieChartData($program = null, $yearLevel = null)
    {
        $query = SurveyResponse::query();

        if ($program) {
            $query->where('program', $program);
        }

        if ($yearLevel) {
            $query->where('year_level', $yearLevel);
        }

        $responses = $query->get();

        $ratingDistribution = [
            '1 Star' => 0,
            '2 Stars' => 0,
            '3 Stars' => 0,
            '4 Stars' => 0,
            '5 Stars' => 0,
        ];

        foreach ($responses as $response) {
            $avgRating = round(($response->course_content_rating +
                              $response->facilities_rating +
                              $response->support_services_rating +
                              $response->overall_satisfaction) / 4);

            $key = $avgRating . ' Star' . ($avgRating > 1 ? 's' : '');
            if (isset($ratingDistribution[$key])) {
                $ratingDistribution[$key]++;
            }
        }

        $data = [
            'labels' => array_keys($ratingDistribution),
            'datasets' => [
                [
                    'data' => array_values($ratingDistribution),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                        'rgba(255, 205, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                    ],
                    'borderWidth' => 1,
                ]
            ]
        ];

        return $data;
    }

    public function generateRadarChartData($program = null, $yearLevel = null)
    {
        $query = SurveyResponse::query();

        if ($program) {
            $query->where('program', $program);
        }

        if ($yearLevel) {
            $query->where('year_level', $yearLevel);
        }

        $responses = $query->get();

        $data = [
            'labels' => ['Course Content', 'Facilities', 'Support Services', 'Overall Satisfaction'],
            'datasets' => [
                [
                    'label' => 'Average Performance',
                    'data' => [
                        round($responses->avg('course_content_rating'), 2),
                        round($responses->avg('facilities_rating'), 2),
                        round($responses->avg('support_services_rating'), 2),
                        round($responses->avg('overall_satisfaction'), 2),
                    ],
                    'fill' => true,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                    'pointBackgroundColor' => 'rgba(54, 162, 235, 1)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgba(54, 162, 235, 1)',
                ]
            ]
        ];

        return $data;
    }

    public function generateWordCloudData($minFrequency = 2)
    {
        $comments = SurveyResponse::whereNotNull('comments')
            ->pluck('comments')
            ->map(function($comment) {
                return decrypt($comment);
            })
            ->filter()
            ->toArray();

        $allWords = [];
        $stopWords = ['the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'an', 'a', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might', 'must', 'can', 'shall'];

        foreach ($comments as $comment) {
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

        $wordCloudData = [];
        $maxCount = max($keywords);
        $minCount = min($keywords);

        foreach ($keywords as $word => $count) {
            $size = 10 + (($count - $minCount) / ($maxCount - $minCount)) * 40; // Size between 10 and 50
            $wordCloudData[] = [
                'text' => $word,
                'size' => round($size),
                'weight' => $count
            ];
        }

        return array_slice($wordCloudData, 0, 50); // Limit to top 50 words
    }

    public function generateProgramComparisonChart()
    {
        $programs = ['BSIT', 'BSIT-BA'];

        $data = [
            'labels' => ['Course Content', 'Facilities', 'Support Services', 'Overall Satisfaction'],
            'datasets' => []
        ];

        $colors = [
            'BSIT' => ['rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 1)'],
            'BSIT-BA' => ['rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 1)']
        ];

        foreach ($programs as $program) {
            $responses = SurveyResponse::where('program', $program)->get();

            if ($responses->count() > 0) {
                $data['datasets'][] = [
                    'label' => $program,
                    'data' => [
                        round($responses->avg('course_content_rating'), 2),
                        round($responses->avg('facilities_rating'), 2),
                        round($responses->avg('support_services_rating'), 2),
                        round($responses->avg('overall_satisfaction'), 2),
                    ],
                    'backgroundColor' => $colors[$program][0],
                    'borderColor' => $colors[$program][1],
                    'borderWidth' => 1,
                ];
            }
        }

        return $data;
    }

    public function generateYearLevelTrendChart()
    {
        $yearLevels = [1, 2, 3, 4];

        $data = [
            'labels' => array_map(function($year) { return $year . 'st Year'; }, $yearLevels),
            'datasets' => [
                [
                    'label' => 'Average Overall Satisfaction',
                    'data' => [],
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'tension' => 0.4,
                ]
            ]
        ];

        foreach ($yearLevels as $year) {
            $responses = SurveyResponse::where('year_level', $year)->get();
            $avgSatisfaction = $responses->count() > 0 ? round($responses->avg('overall_satisfaction'), 2) : 0;
            $data['datasets'][0]['data'][] = $avgSatisfaction;
        }

        return $data;
    }
}
