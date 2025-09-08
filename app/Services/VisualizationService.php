<?php

namespace App\Services;

use App\Models\SurveyResponse;

class VisualizationService
{
    public function generateBarChartData($track = null, $gradeLevel = null, $academicYear = null, $semester = null)
    {
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

        // ISO 21001 Composite Indices for Bar Chart
        $categories = ['Learner Needs', 'Satisfaction', 'Success', 'Safety', 'Wellbeing', 'Overall Satisfaction'];
        $data = [
            'labels' => $categories,
            'datasets' => [
                [
                    'label' => 'ISO 21001 Indices (1-5)',
                    'data' => [
                        round(($responses->avg('curriculum_relevance_rating') +
                               $responses->avg('learning_pace_appropriateness') +
                               $responses->avg('individual_support_availability') +
                               $responses->avg('learning_style_accommodation')) / 4, 2),
                        round(($responses->avg('teaching_quality_rating') +
                               $responses->avg('learning_environment_rating') +
                               $responses->avg('peer_interaction_satisfaction') +
                               $responses->avg('extracurricular_satisfaction')) / 4, 2),
                        round(($responses->avg('academic_progress_rating') +
                               $responses->avg('skill_development_rating') +
                               $responses->avg('critical_thinking_improvement') +
                               $responses->avg('problem_solving_confidence')) / 4, 2),
                        round(($responses->avg('physical_safety_rating') +
                               $responses->avg('psychological_safety_rating') +
                               $responses->avg('bullying_prevention_effectiveness') +
                               $responses->avg('emergency_preparedness_rating')) / 4, 2),
                        round(($responses->avg('mental_health_support_rating') +
                               $responses->avg('stress_management_support') +
                               $responses->avg('physical_health_support') +
                               $responses->avg('overall_wellbeing_rating')) / 4, 2),
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

    public function generatePieChartData($track = null, $gradeLevel = null, $academicYear = null, $semester = null)
    {
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

        $ratingDistribution = [
            '1 Star' => 0,
            '2 Stars' => 0,
            '3 Stars' => 0,
            '4 Stars' => 0,
            '5 Stars' => 0,
        ];

        foreach ($responses as $response) {
            // Calculate overall ISO 21001 satisfaction index
            $overallIndex = round((
                $response->overall_satisfaction +
                round(($response->curriculum_relevance_rating + $response->learning_pace_appropriateness +
                       $response->individual_support_availability + $response->learning_style_accommodation) / 4) +
                round(($response->teaching_quality_rating + $response->learning_environment_rating +
                       $response->peer_interaction_satisfaction + $response->extracurricular_satisfaction) / 4) +
                round(($response->physical_safety_rating + $response->psychological_safety_rating +
                       $response->bullying_prevention_effectiveness + $response->emergency_preparedness_rating) / 4) +
                round(($response->mental_health_support_rating + $response->stress_management_support +
                       $response->physical_health_support + $response->overall_wellbeing_rating) / 4)
            ) / 5);

            $key = $overallIndex . ' Star' . ($overallIndex > 1 ? 's' : '');
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

    public function generateRadarChartData($track = null, $gradeLevel = null, $academicYear = null, $semester = null)
    {
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

        // ISO 21001 Indices for Radar Chart
        $data = [
            'labels' => ['Learner Needs', 'Satisfaction', 'Success', 'Safety', 'Wellbeing'],
            'datasets' => [
                [
                    'label' => 'ISO 21001 Performance Profile',
                    'data' => [
                        round(($responses->avg('curriculum_relevance_rating') +
                               $responses->avg('learning_pace_appropriateness') +
                               $responses->avg('individual_support_availability') +
                               $responses->avg('learning_style_accommodation')) / 4, 2),
                        round(($responses->avg('teaching_quality_rating') +
                               $responses->avg('learning_environment_rating') +
                               $responses->avg('peer_interaction_satisfaction') +
                               $responses->avg('extracurricular_satisfaction')) / 4, 2),
                        round(($responses->avg('academic_progress_rating') +
                               $responses->avg('skill_development_rating') +
                               $responses->avg('critical_thinking_improvement') +
                               $responses->avg('problem_solving_confidence')) / 4, 2),
                        round(($responses->avg('physical_safety_rating') +
                               $responses->avg('psychological_safety_rating') +
                               $responses->avg('bullying_prevention_effectiveness') +
                               $responses->avg('emergency_preparedness_rating')) / 4, 2),
                        round(($responses->avg('mental_health_support_rating') +
                               $responses->avg('stress_management_support') +
                               $responses->avg('physical_health_support') +
                               $responses->avg('overall_wellbeing_rating')) / 4, 2),
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

    public function generateWordCloudData($track = null, $gradeLevel = null, $academicYear = null, $semester = null, $minFrequency = 2)
    {
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

        // Combine all text fields for comprehensive word cloud
        $textFields = ['positive_aspects', 'improvement_suggestions', 'additional_comments'];
        $allComments = $query->where(function($q) use ($textFields) {
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

        $allWords = [];
        $stopWords = ['the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'an', 'a', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might', 'must', 'can', 'shall', 'i', 'you', 'he', 'she', 'it', 'we', 'they', 'this', 'that', 'these', 'those', 'my', 'your', 'his', 'her', 'its', 'our', 'their', 'me', 'him', 'her', 'us', 'them'];

        foreach ($allComments as $comment) {
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
        $maxCount = max($keywords) ?? 1;
        $minCount = min($keywords) ?? 0;

        foreach ($keywords as $word => $count) {
            $size = 10 + (($count - $minCount) / ($maxCount - $minCount)) * 40; // Size between 10 and 50
            $wordCloudData[] = [
                'text' => ucfirst($word),
                'size' => round($size),
                'weight' => $count
            ];
        }

        return array_slice($wordCloudData, 0, 50); // Limit to top 50 words
    }

    public function generateTrackComparisonChart()
    {
        // Since we only have STEM track currently, compare by grade level or academic year
        $gradeLevels = [11, 12];

        $data = [
            'labels' => ['Learner Needs', 'Satisfaction', 'Success', 'Safety', 'Wellbeing'],
            'datasets' => []
        ];

        $colors = [
            'Grade 11' => ['rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 1)'],
            'Grade 12' => ['rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 1)']
        ];

        foreach ($gradeLevels as $grade) {
            $responses = SurveyResponse::where('grade_level', $grade)->get();

            if ($responses->count() > 0) {
                $data['datasets'][] = [
                    'label' => 'Grade ' . $grade,
                    'data' => [
                        round(($responses->avg('curriculum_relevance_rating') +
                               $responses->avg('learning_pace_appropriateness') +
                               $responses->avg('individual_support_availability') +
                               $responses->avg('learning_style_accommodation')) / 4, 2),
                        round(($responses->avg('teaching_quality_rating') +
                               $responses->avg('learning_environment_rating') +
                               $responses->avg('peer_interaction_satisfaction') +
                               $responses->avg('extracurricular_satisfaction')) / 4, 2),
                        round(($responses->avg('academic_progress_rating') +
                               $responses->avg('skill_development_rating') +
                               $responses->avg('critical_thinking_improvement') +
                               $responses->avg('problem_solving_confidence')) / 4, 2),
                        round(($responses->avg('physical_safety_rating') +
                               $responses->avg('psychological_safety_rating') +
                               $responses->avg('bullying_prevention_effectiveness') +
                               $responses->avg('emergency_preparedness_rating')) / 4, 2),
                        round(($responses->avg('mental_health_support_rating') +
                               $responses->avg('stress_management_support') +
                               $responses->avg('physical_health_support') +
                               $responses->avg('overall_wellbeing_rating')) / 4, 2),
                    ],
                    'backgroundColor' => $colors['Grade ' . $grade][0],
                    'borderColor' => $colors['Grade ' . $grade][1],
                    'borderWidth' => 1,
                ];
            }
        }

        return $data;
    }

    public function generateGradeLevelTrendChart($academicYear = null, $semester = null)
    {
        $gradeLevels = [11, 12];

        $data = [
            'labels' => ['Grade 11', 'Grade 12'],
            'datasets' => [
                [
                    'label' => 'Overall Satisfaction Trend',
                    'data' => [],
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Wellbeing Index Trend',
                    'data' => [],
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Average GPA Trend',
                    'data' => [],
                    'borderColor' => 'rgba(255, 159, 64, 1)',
                    'backgroundColor' => 'rgba(255, 159, 64, 0.2)',
                    'tension' => 0.4,
                ]
            ]
        ];

        foreach ($gradeLevels as $grade) {
            $query = SurveyResponse::where('grade_level', $grade);

            if ($academicYear) {
                $query->where('academic_year', $academicYear);
            }

            if ($semester) {
                $query->where('semester', $semester);
            }

            $responses = $query->get();

            if ($responses->count() > 0) {
                $data['datasets'][0]['data'][] = round($responses->avg('overall_satisfaction'), 2);

                // Wellbeing Index
                $wellbeingAvg = round((
                    $responses->avg('mental_health_support_rating') +
                    $responses->avg('stress_management_support') +
                    $responses->avg('physical_health_support') +
                    $responses->avg('overall_wellbeing_rating')
                ) / 4, 2);
                $data['datasets'][1]['data'][] = $wellbeingAvg;

                // Average GPA
                $avgGPA = $responses->avg('grade_average') ?? 0;
                $data['datasets'][2]['data'][] = round($avgGPA, 2);
            } else {
                $data['datasets'][0]['data'][] = 0;
                $data['datasets'][1]['data'][] = 0;
                $data['datasets'][2]['data'][] = 0;
            }
        }

        return $data;
    }
}
