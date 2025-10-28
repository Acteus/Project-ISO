<?php

namespace App\Services;

use App\Models\SurveyResponse;
use Illuminate\Support\Facades\DB;

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

    /**
     * Generate time-series trend data for specified metrics over time periods
     */
    public function generateTimeSeriesData($metric, $dateFrom = null, $dateTo = null, $groupBy = 'week')
    {
        $query = SurveyResponse::query();

        if ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('created_at', '<=', $dateTo);
        }

        $responses = $query->orderBy('created_at', 'asc')->get();

        // If no responses, return empty arrays
        if ($responses->isEmpty()) {
            return [
                'labels' => [],
                'data' => [],
                'metric' => $metric,
                'group_by' => $groupBy,
                'date_range' => [
                    'from' => $dateFrom,
                    'to' => $dateTo
                ]
            ];
        }

        // Group responses by time period with proper date formatting
        $groupedData = $responses->groupBy(function($response) use ($groupBy) {
            switch ($groupBy) {
                case 'day':
                    return $response->created_at->format('Y-m-d');
                case 'week':
                    return $response->created_at->format('Y') . '-W' . $response->created_at->format('W');
                case 'month':
                    return $response->created_at->format('Y-m');
                case 'year':
                    return $response->created_at->format('Y');
                default:
                    return $response->created_at->format('Y') . '-W' . $response->created_at->format('W');
            }
        });

        $labels = [];
        $data = [];

        foreach ($groupedData as $period => $periodResponses) {
            $labels[] = $period;

            // Calculate metric value based on metric type
            switch ($metric) {
                case 'overall_satisfaction':
                    $data[] = round($periodResponses->avg('overall_satisfaction'), 2);
                    break;
                case 'learner_needs':
                    $data[] = round(($periodResponses->avg('curriculum_relevance_rating') +
                                     $periodResponses->avg('learning_pace_appropriateness') +
                                     $periodResponses->avg('individual_support_availability') +
                                     $periodResponses->avg('learning_style_accommodation')) / 4, 2);
                    break;
                case 'satisfaction':
                    $data[] = round(($periodResponses->avg('teaching_quality_rating') +
                                     $periodResponses->avg('learning_environment_rating') +
                                     $periodResponses->avg('peer_interaction_satisfaction') +
                                     $periodResponses->avg('extracurricular_satisfaction')) / 4, 2);
                    break;
                case 'success':
                    $data[] = round(($periodResponses->avg('academic_progress_rating') +
                                     $periodResponses->avg('skill_development_rating') +
                                     $periodResponses->avg('critical_thinking_improvement') +
                                     $periodResponses->avg('problem_solving_confidence')) / 4, 2);
                    break;
                case 'safety':
                    $data[] = round(($periodResponses->avg('physical_safety_rating') +
                                     $periodResponses->avg('psychological_safety_rating') +
                                     $periodResponses->avg('bullying_prevention_effectiveness') +
                                     $periodResponses->avg('emergency_preparedness_rating')) / 4, 2);
                    break;
                case 'wellbeing':
                    $data[] = round(($periodResponses->avg('mental_health_support_rating') +
                                     $periodResponses->avg('stress_management_support') +
                                     $periodResponses->avg('physical_health_support') +
                                     $periodResponses->avg('overall_wellbeing_rating')) / 4, 2);
                    break;
                case 'response_count':
                    $data[] = $periodResponses->count();
                    break;
                default:
                    $data[] = round($periodResponses->avg($metric), 2);
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'metric' => $metric,
            'group_by' => $groupBy,
            'date_range' => [
                'from' => $dateFrom,
                'to' => $dateTo
            ]
        ];
    }

    /**
     * Generate heat map data for performance by track and grade level
     */
    public function generateHeatMapData($metric = 'overall_satisfaction', $dateFrom = null, $dateTo = null)
    {
        $tracks = ['CSS']; // Current tracks
        $gradeLevels = [11, 12];

        $heatMapData = [];

        foreach ($tracks as $track) {
            foreach ($gradeLevels as $grade) {
                $query = SurveyResponse::where('track', $track)
                    ->where('grade_level', $grade);

                // Apply date filters
                if ($dateFrom && $dateTo) {
                    $query->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
                } elseif ($dateFrom) {
                    $query->where('created_at', '>=', $dateFrom . ' 00:00:00');
                } elseif ($dateTo) {
                    $query->where('created_at', '<=', $dateTo . ' 23:59:59');
                }

                $responses = $query->get();

                if ($responses->count() > 0) {
                    $value = 0;
                    switch ($metric) {
                        case 'overall_satisfaction':
                            $value = round($responses->avg('overall_satisfaction'), 2);
                            break;
                        case 'learner_needs':
                            $value = round(($responses->avg('curriculum_relevance_rating') +
                                           $responses->avg('learning_pace_appropriateness') +
                                           $responses->avg('individual_support_availability') +
                                           $responses->avg('learning_style_accommodation')) / 4, 2);
                            break;
                        case 'safety':
                            $value = round(($responses->avg('physical_safety_rating') +
                                           $responses->avg('psychological_safety_rating') +
                                           $responses->avg('bullying_prevention_effectiveness') +
                                           $responses->avg('emergency_preparedness_rating')) / 4, 2);
                            break;
                    }

                    $heatMapData[] = [
                        'track' => $track,
                        'grade_level' => $grade,
                        'value' => $value,
                        'count' => $responses->count()
                    ];
                }
            }
        }

        return [
            'data' => $heatMapData,
            'metric' => $metric,
            'tracks' => $tracks,
            'grade_levels' => $gradeLevels
        ];
    }

    /**
     * Generate compliance risk meter data
     */
    public function generateComplianceRiskData($dateFrom = null, $dateTo = null)
    {
        $query = SurveyResponse::query();

        // Apply date filters
        if ($dateFrom && $dateTo) {
            $query->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        } elseif ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom . ' 00:00:00');
        } elseif ($dateTo) {
            $query->where('created_at', '<=', $dateTo . ' 23:59:59');
        }

        $responses = $query->get();

        if ($responses->isEmpty()) {
            return [
                'risk_level' => 'Unknown',
                'risk_score' => 0,
                'compliance_percentage' => 0,
                'recommendations' => []
            ];
        }

        // Calculate ISO 21001 indices
        $learnerNeeds = round(($responses->avg('curriculum_relevance_rating') +
                               $responses->avg('learning_pace_appropriateness') +
                               $responses->avg('individual_support_availability') +
                               $responses->avg('learning_style_accommodation')) / 4, 2);

        $satisfaction = round(($responses->avg('teaching_quality_rating') +
                               $responses->avg('learning_environment_rating') +
                               $responses->avg('peer_interaction_satisfaction') +
                               $responses->avg('extracurricular_satisfaction')) / 4, 2);

        $success = round(($responses->avg('academic_progress_rating') +
                          $responses->avg('skill_development_rating') +
                          $responses->avg('critical_thinking_improvement') +
                          $responses->avg('problem_solving_confidence')) / 4, 2);

        $safety = round(($responses->avg('physical_safety_rating') +
                         $responses->avg('psychological_safety_rating') +
                         $responses->avg('bullying_prevention_effectiveness') +
                         $responses->avg('emergency_preparedness_rating')) / 4, 2);

        $wellbeing = round(($responses->avg('mental_health_support_rating') +
                            $responses->avg('stress_management_support') +
                            $responses->avg('physical_health_support') +
                            $responses->avg('overall_wellbeing_rating')) / 4, 2);

        // Calculate weighted compliance score
        $complianceScore = (
            $learnerNeeds * 0.15 +
            $satisfaction * 0.25 +
            $success * 0.20 +
            $safety * 0.20 +
            $wellbeing * 0.15 +
            $responses->avg('overall_satisfaction') * 0.05
        );

        $compliancePercentage = round(($complianceScore / 5) * 100, 2);

        // Determine risk level
        if ($complianceScore >= 4.2) {
            $riskLevel = 'Low';
            $riskScore = 1;
        } elseif ($complianceScore >= 3.5) {
            $riskLevel = 'Medium';
            $riskScore = 2;
        } else {
            $riskLevel = 'High';
            $riskScore = 3;
        }

        // Generate recommendations
        $recommendations = [];
        if ($safety < 3.5) {
            $recommendations[] = 'URGENT: Enhance safety protocols and emergency preparedness';
        }
        if ($wellbeing < 3.5) {
            $recommendations[] = 'PRIORITY: Strengthen wellbeing support programs';
        }
        if ($satisfaction < 3.5) {
            $recommendations[] = 'IMMEDIATE: Address learner satisfaction concerns';
        }
        if (empty($recommendations)) {
            $recommendations[] = 'Continue monitoring and maintaining high standards';
        }

        return [
            'risk_level' => $riskLevel,
            'risk_score' => $riskScore,
            'compliance_score' => round($complianceScore, 2),
            'compliance_percentage' => $compliancePercentage,
            'indices' => [
                'learner_needs' => $learnerNeeds,
                'satisfaction' => $satisfaction,
                'success' => $success,
                'safety' => $safety,
                'wellbeing' => $wellbeing
            ],
            'recommendations' => $recommendations,
            'total_responses' => $responses->count()
        ];
    }

    /**
     * Generate comparative period analysis (current vs previous)
     */
    public function generateComparativeAnalysis($currentDateFrom, $currentDateTo, $previousDateFrom, $previousDateTo)
    {
        $currentResponses = SurveyResponse::whereBetween('created_at', [$currentDateFrom, $currentDateTo])->get();
        $previousResponses = SurveyResponse::whereBetween('created_at', [$previousDateFrom, $previousDateTo])->get();

        $compareMetrics = function($current, $previous, $metric) {
            $currentVal = $current->avg($metric) ?? 0;
            $previousVal = $previous->avg($metric) ?? 0;
            $change = $currentVal - $previousVal;
            $percentChange = $previousVal > 0 ? round(($change / $previousVal) * 100, 2) : 0;

            return [
                'current' => round($currentVal, 2),
                'previous' => round($previousVal, 2),
                'change' => round($change, 2),
                'percent_change' => $percentChange,
                'trend' => $change > 0 ? 'up' : ($change < 0 ? 'down' : 'stable')
            ];
        };

        return [
            'overall_satisfaction' => $compareMetrics($currentResponses, $previousResponses, 'overall_satisfaction'),
            'safety_index' => [
                'current' => round(($currentResponses->avg('physical_safety_rating') + $currentResponses->avg('psychological_safety_rating')) / 2, 2),
                'previous' => round(($previousResponses->avg('physical_safety_rating') + $previousResponses->avg('psychological_safety_rating')) / 2, 2)
            ],
            'response_count' => [
                'current' => $currentResponses->count(),
                'previous' => $previousResponses->count(),
                'change' => $currentResponses->count() - $previousResponses->count()
            ],
            'date_ranges' => [
                'current' => [$currentDateFrom, $currentDateTo],
                'previous' => [$previousDateFrom, $previousDateTo]
            ]
        ];
    }

    /**
     * Generate response rate analytics
     */
    public function generateResponseRateAnalytics($dateFrom = null, $dateTo = null)
    {
        $query = SurveyResponse::query();

        // Apply date filters
        if ($dateFrom && $dateTo) {
            $query->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        } elseif ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom . ' 00:00:00');
        } elseif ($dateTo) {
            $query->where('created_at', '<=', $dateTo . ' 23:59:59');
        }

        $totalResponses = $query->count();

        $byTrackQuery = SurveyResponse::select('track', DB::raw('count(*) as count'))
            ->groupBy('track');

        // Apply date filters to byTrack query
        if ($dateFrom && $dateTo) {
            $byTrackQuery->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        } elseif ($dateFrom) {
            $byTrackQuery->where('created_at', '>=', $dateFrom . ' 00:00:00');
        } elseif ($dateTo) {
            $byTrackQuery->where('created_at', '<=', $dateTo . ' 23:59:59');
        }

        $byTrack = $byTrackQuery->get()
            ->pluck('count', 'track');

        $byGradeQuery = SurveyResponse::select('grade_level', DB::raw('count(*) as count'))
            ->groupBy('grade_level');

        // Apply date filters to byGrade query
        if ($dateFrom && $dateTo) {
            $byGradeQuery->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        } elseif ($dateFrom) {
            $byGradeQuery->where('created_at', '>=', $dateFrom . ' 00:00:00');
        } elseif ($dateTo) {
            $byGradeQuery->where('created_at', '<=', $dateTo . ' 23:59:59');
        }

        $byGrade = $byGradeQuery->get()
            ->pluck('count', 'grade_level');

        $bySemesterQuery = SurveyResponse::select('semester', DB::raw('count(*) as count'))
            ->groupBy('semester');

        // Apply date filters to bySemester query
        if ($dateFrom && $dateTo) {
            $bySemesterQuery->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        } elseif ($dateFrom) {
            $bySemesterQuery->where('created_at', '>=', $dateFrom . ' 00:00:00');
        } elseif ($dateTo) {
            $bySemesterQuery->where('created_at', '<=', $dateTo . ' 23:59:59');
        }

        $bySemester = $bySemesterQuery->get()
            ->pluck('count', 'semester');

        $byGenderQuery = SurveyResponse::select('gender', DB::raw('count(*) as count'))
            ->whereNotNull('gender')
            ->groupBy('gender');

        // Apply date filters to byGender query
        if ($dateFrom && $dateTo) {
            $byGenderQuery->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        } elseif ($dateFrom) {
            $byGenderQuery->where('created_at', '>=', $dateFrom . ' 00:00:00');
        } elseif ($dateTo) {
            $byGenderQuery->where('created_at', '<=', $dateTo . ' 23:59:59');
        }

        $byGender = $byGenderQuery->get()
            ->pluck('count', 'gender');

        // Completion rate by time period (don't apply custom date filters to these)
        $last7DaysQuery = SurveyResponse::where('created_at', '>=', now()->subDays(7));
        $last30DaysQuery = SurveyResponse::where('created_at', '>=', now()->subDays(30));
        $last90DaysQuery = SurveyResponse::where('created_at', '>=', now()->subDays(90));

        $last7Days = $last7DaysQuery->count();
        $last30Days = $last30DaysQuery->count();
        $last90Days = $last90DaysQuery->count();

        return [
            'total_responses' => $totalResponses,
            'by_track' => $byTrack,
            'by_grade_level' => $byGrade,
            'by_semester' => $bySemester,
            'by_gender' => $byGender,
            'time_periods' => [
                'last_7_days' => $last7Days,
                'last_30_days' => $last30Days,
                'last_90_days' => $last90Days
            ]
        ];
    }
}
