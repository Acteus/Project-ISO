<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SurveyResponse>
 *
 * Enhanced Factory for AI Insights Testing
 *
 * Creates realistic survey responses with:
 * - Diverse student profiles (high performers, struggling students, average students)
 * - Varied sentiment in comments (positive, negative, neutral)
 * - Realistic correlations between metrics
 * - Time-based distribution for trend analysis
 * - All ISO 21001 fields properly populated
 */
class SurveyResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Determine student profile type for realistic data correlation
        // This helps create distinct clusters for AI analysis
        $profileType = fake()->randomElement(['high_performer', 'average', 'struggling', 'high_performer', 'average']);

        // Generate base rating based on profile type
        $baseRating = match($profileType) {
            'high_performer' => fake()->randomElement([4, 4, 5, 5, 5, 5]),
            'struggling' => fake()->randomElement([1, 1, 2, 2, 3, 3]),
            default => fake()->randomElement([3, 3, 4, 4, 4, 4]),
        };

        // Generate ratings with correlation (similar ratings for related fields)
        $generateCorrelatedRating = function($base, $variation = 1) use ($profileType) {
            $min = max(1, $base - $variation);
            $max = min(5, $base + $variation);

            // Struggling students have more variation
            if ($profileType === 'struggling') {
                return fake()->numberBetween($min, min(3, $max));
            }

            return fake()->numberBetween($min, $max);
        };

        $gradeLevel = fake()->randomElement([11, 12]);
        $currentYear = date('Y');
        $academicYear = $currentYear . '-' . ($currentYear + 1);
        $semester = fake()->randomElement(['1st', '2nd']);

        // Calculate overall satisfaction based on profile
        $overallSatisfaction = match($profileType) {
            'high_performer' => fake()->randomElement([4, 4, 5, 5, 5]),
            'struggling' => fake()->randomElement([1, 2, 2, 3, 3]),
            default => fake()->randomElement([3, 3, 4, 4, 4]),
        };

        // Generate comments with varied sentiment based on profile
        $sentimentComments = $this->generateSentimentComments($profileType, $overallSatisfaction);

        // Generate indirect metrics correlated with profile
        $indirectMetrics = $this->generateIndirectMetrics($profileType, $overallSatisfaction);

        // Generate timestamps for time-series analysis
        $createdAt = fake()->dateTimeBetween('-180 days', 'now');
        $updatedAt = fake()->dateTimeBetween($createdAt, 'now');

        return [
            'student_id' => 'CSS-' . $gradeLevel . '-' . fake()->unique()->numerify('####'),
            'track' => 'CSS',
            'grade_level' => $gradeLevel,
            'academic_year' => $academicYear,
            'semester' => $semester,
            'gender' => fake()->randomElement(['Male', 'Female', 'Non-binary', 'Prefer not to say']),

            // ISO 21001 Learner Needs Assessment (4 fields)
            'curriculum_relevance_rating' => $generateCorrelatedRating($baseRating),
            'learning_pace_appropriateness' => $generateCorrelatedRating($baseRating),
            'individual_support_availability' => $generateCorrelatedRating($baseRating, 2), // More variation
            'learning_style_accommodation' => $generateCorrelatedRating($baseRating),

            // ISO 21001 Learner Satisfaction Metrics (4 fields)
            'teaching_quality_rating' => $generateCorrelatedRating($baseRating),
            'learning_environment_rating' => $generateCorrelatedRating($baseRating),
            'peer_interaction_satisfaction' => $generateCorrelatedRating($baseRating, 2),
            'extracurricular_satisfaction' => $generateCorrelatedRating($baseRating, 2),

            // ISO 21001 Learner Success Indicators (4 fields)
            'academic_progress_rating' => $generateCorrelatedRating($baseRating),
            'skill_development_rating' => $generateCorrelatedRating($baseRating),
            'critical_thinking_improvement' => $generateCorrelatedRating($baseRating),
            'problem_solving_confidence' => $generateCorrelatedRating($baseRating),

            // ISO 21001 Learner Safety Assessment (4 fields)
            'physical_safety_rating' => fake()->randomElement([4, 4, 5, 5, 5, 3]), // Generally high for safety
            'psychological_safety_rating' => $generateCorrelatedRating($baseRating),
            'bullying_prevention_effectiveness' => fake()->randomElement([4, 4, 5, 5, 3, 3]),
            'emergency_preparedness_rating' => fake()->randomElement([4, 4, 5, 5, 4, 3]),

            // ISO 21001 Learner Wellbeing Metrics (4 fields)
            'mental_health_support_rating' => $generateCorrelatedRating($baseRating, 2),
            'stress_management_support' => $generateCorrelatedRating($baseRating, 2),
            'physical_health_support' => $generateCorrelatedRating($baseRating, 2),
            'overall_wellbeing_rating' => $generateCorrelatedRating($baseRating),

            // Overall Satisfaction
            'overall_satisfaction' => $overallSatisfaction,

            // Qualitative Feedback with Varied Sentiment
            'positive_aspects' => $sentimentComments['positive'],
            'improvement_suggestions' => $sentimentComments['suggestions'],
            'additional_comments' => $sentimentComments['additional'],

            // Additional Feedback Fields (ISO 21001 compliant)
            'feedback_taken_seriously' => $generateCorrelatedRating($baseRating, 2),
            'school_responsiveness' => $generateCorrelatedRating($baseRating, 2),
            'visible_improvements' => $generateCorrelatedRating($baseRating, 2),

            // Consent and Privacy
            'consent_given' => true,
            'ip_address' => fake()->ipv4,

            // Indirect Metrics (correlated with profile type)
            'attendance_rate' => $indirectMetrics['attendance_rate'],
            'grade_average' => $indirectMetrics['grade_average'],
            'participation_score' => $indirectMetrics['participation_score'],
            'extracurricular_hours' => $indirectMetrics['extracurricular_hours'],
            'counseling_sessions' => $indirectMetrics['counseling_sessions'],

            // Timestamps - spread responses across the last 180 days for better trend analysis
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }

    /**
     * Generate sentiment-based comments based on student profile
     */
    protected function generateSentimentComments(string $profileType, int $overallSatisfaction): array
    {
        $hasComments = fake()->boolean(90); // 90% have comments

        if (!$hasComments) {
            return [
                'positive' => null,
                'suggestions' => null,
                'additional' => null,
            ];
        }

        $positiveComments = [
            'high_performer' => [
                'The instructors are excellent and very supportive. They really know their subject matter.',
                'I love the hands-on training and practical exercises. The curriculum is comprehensive and relevant.',
                'The facilities and equipment are modern and well-maintained. Great learning environment!',
                'Excellent career guidance and industry connections. The program has built my confidence.',
                'The CSS program is amazing! I feel well-prepared for my future career in IT.',
                'Teachers are very knowledgeable and always willing to help. Great support system.',
                'The program covers all essential technical skills. Very satisfied with the quality.',
                'Hands-on approach to learning is perfect. I can see my skills improving every day.',
            ],
            'average' => [
                'The program is good overall. Instructors are decent and facilities are adequate.',
                'I appreciate the practical exercises, though more equipment would be helpful.',
                'The curriculum is comprehensive, but some topics could be explained better.',
                'Good program with room for improvement. Overall satisfied with my progress.',
                'The instructors are supportive, but I wish there were more resources available.',
                'Decent learning environment. The program meets my basic expectations.',
            ],
            'struggling' => [
                'The program is challenging. I need more support to understand the concepts.',
                'Some instructors are helpful, but I struggle with the pace of learning.',
                'The facilities are okay, but I need more practice time and guidance.',
                'I find it difficult to keep up. More tutorial sessions would be beneficial.',
                'The curriculum is complex. I need additional help to succeed.',
            ],
        ];

        $suggestionComments = [
            'high_performer' => [
                'Could benefit from more advanced topics and industry certifications.',
                'More internship opportunities with tech companies would be great.',
                'Would like to see more focus on emerging technologies like AI and cloud computing.',
                'More guest speakers from the industry would enhance the learning experience.',
            ],
            'average' => [
                'More equipment for hands-on practice would be helpful.',
                'Better internet connectivity in labs would improve the learning experience.',
                'Additional tutorial sessions for struggling students would be beneficial.',
                'More software licenses for home practice would help.',
                'Could use more industry internship opportunities.',
            ],
            'struggling' => [
                'Need more one-on-one support and slower-paced instruction.',
                'More practice materials and step-by-step guides would help.',
                'Additional tutorial sessions are essential for students like me.',
                'Better explanation of complex topics is needed.',
                'More time for assignments and projects would be helpful.',
            ],
        ];

        $additionalComments = [
            'high_performer' => [
                'The CSS program has greatly improved my technical skills. Highly recommend!',
                'I appreciate the hands-on approach and excellent instruction.',
                'This program prepares us well for technical certifications and careers.',
                'Overall, I am very satisfied with the quality of education.',
                'The school provides an excellent balance of theory and practice.',
            ],
            'average' => [
                'The CSS program is good, but there is room for improvement.',
                'I appreciate the hands-on learning, though more resources would help.',
                'The program is decent overall. Some improvements would make it better.',
                'Satisfied with my progress, but more support would be beneficial.',
            ],
            'struggling' => [
                'The program is difficult, but I am trying my best to succeed.',
                'I need more help and support to keep up with the curriculum.',
                'Finding it challenging, but the instructors are trying to help.',
                'Hope to see improvements in support for struggling students.',
            ],
        ];

        $profile = $profileType === 'high_performer' ? 'high_performer' :
                   ($overallSatisfaction <= 2 ? 'struggling' : 'average');

        return [
            'positive' => fake()->optional(85)->randomElement($positiveComments[$profile] ?? $positiveComments['average']),
            'suggestions' => fake()->optional(75)->randomElement($suggestionComments[$profile] ?? $suggestionComments['average']),
            'additional' => fake()->optional(70)->randomElement($additionalComments[$profile] ?? $additionalComments['average']),
        ];
    }

    /**
     * Generate indirect metrics correlated with student profile
     */
    protected function generateIndirectMetrics(string $profileType, int $overallSatisfaction): array
    {
        return match($profileType) {
            'high_performer' => [
                'attendance_rate' => fake()->randomFloat(1, 90, 100),
                'grade_average' => fake()->randomFloat(2, 3.5, 4.0), // High GPA
                'participation_score' => fake()->numberBetween(85, 100),
                'extracurricular_hours' => fake()->numberBetween(5, 20),
                'counseling_sessions' => fake()->numberBetween(0, 2),
            ],
            'struggling' => [
                'attendance_rate' => fake()->randomFloat(1, 60, 85),
                'grade_average' => fake()->randomFloat(2, 1.5, 2.5), // Lower GPA
                'participation_score' => fake()->numberBetween(50, 75),
                'extracurricular_hours' => fake()->numberBetween(0, 5),
                'counseling_sessions' => fake()->numberBetween(2, 8), // More counseling
            ],
            default => [
                'attendance_rate' => fake()->randomFloat(1, 75, 95),
                'grade_average' => fake()->randomFloat(2, 2.5, 3.5), // Average GPA
                'participation_score' => fake()->numberBetween(70, 90),
                'extracurricular_hours' => fake()->numberBetween(2, 10),
                'counseling_sessions' => fake()->numberBetween(0, 4),
            ],
        };
    }

    /**
     * Create a high-performing student response
     */
    public function highPerformer(): static
    {
        return $this->state(fn (array $attributes) => [
            'curriculum_relevance_rating' => fake()->randomElement([4, 5, 5, 5]),
            'teaching_quality_rating' => fake()->randomElement([4, 5, 5, 5]),
            'overall_satisfaction' => fake()->randomElement([4, 5, 5, 5]),
            'attendance_rate' => fake()->randomFloat(1, 90, 100),
            'grade_average' => fake()->randomFloat(2, 3.5, 4.0),
            'participation_score' => fake()->numberBetween(85, 100),
        ]);
    }

    /**
     * Create a struggling student response
     */
    public function struggling(): static
    {
        return $this->state(fn (array $attributes) => [
            'curriculum_relevance_rating' => fake()->randomElement([1, 2, 2, 3]),
            'teaching_quality_rating' => fake()->randomElement([1, 2, 3, 3]),
            'overall_satisfaction' => fake()->randomElement([1, 2, 2, 3]),
            'attendance_rate' => fake()->randomFloat(1, 60, 80),
            'grade_average' => fake()->randomFloat(2, 1.5, 2.5),
            'participation_score' => fake()->numberBetween(50, 75),
            'counseling_sessions' => fake()->numberBetween(3, 8),
        ]);
    }
}
