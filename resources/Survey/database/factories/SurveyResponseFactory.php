<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SurveyResponse>
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
        return [
            'student_id' => 'STU' . fake()->unique()->numerify('######'),
            'track' => 'STEM',
            'grade_level' => fake()->randomElement([11, 12]),
            'academic_year' => fake()->randomElement(['2023-2024', '2024-2025']),
            'semester' => fake()->randomElement(['1st', '2nd']),

            // ISO 21001 Learner Needs Assessment (1-5 scale)
            'curriculum_relevance_rating' => fake()->numberBetween(1, 5),
            'learning_pace_appropriateness' => fake()->numberBetween(1, 5),
            'individual_support_availability' => fake()->numberBetween(1, 5),
            'learning_style_accommodation' => fake()->numberBetween(1, 5),

            // ISO 21001 Learner Satisfaction Metrics (1-5 scale)
            'teaching_quality_rating' => fake()->numberBetween(1, 5),
            'learning_environment_rating' => fake()->numberBetween(1, 5),
            'peer_interaction_satisfaction' => fake()->numberBetween(1, 5),
            'extracurricular_satisfaction' => fake()->numberBetween(1, 5),

            // ISO 21001 Learner Success Indicators (1-5 scale)
            'academic_progress_rating' => fake()->numberBetween(1, 5),
            'skill_development_rating' => fake()->numberBetween(1, 5),
            'critical_thinking_improvement' => fake()->numberBetween(1, 5),
            'problem_solving_confidence' => fake()->numberBetween(1, 5),

            // ISO 21001 Learner Safety Assessment (1-5 scale)
            'physical_safety_rating' => fake()->numberBetween(1, 5),
            'psychological_safety_rating' => fake()->numberBetween(1, 5),
            'bullying_prevention_effectiveness' => fake()->numberBetween(1, 5),
            'emergency_preparedness_rating' => fake()->numberBetween(1, 5),

            // ISO 21001 Learner Wellbeing Metrics (1-5 scale)
            'mental_health_support_rating' => fake()->numberBetween(1, 5),
            'stress_management_support' => fake()->numberBetween(1, 5),
            'physical_health_support' => fake()->numberBetween(1, 5),
            'overall_wellbeing_rating' => fake()->numberBetween(1, 5),

            // Overall Satisfaction and Feedback
            'overall_satisfaction' => fake()->numberBetween(1, 5),
            'positive_aspects' => fake()->optional()->sentence(),
            'improvement_suggestions' => fake()->optional()->sentence(),
            'additional_comments' => fake()->optional()->paragraph(),

            // Consent and Privacy
            'consent_given' => true,
            'ip_address' => fake()->ipv4,

            // Indirect Metrics (optional but provide defaults)
            'attendance_rate' => fake()->randomFloat(1, 0, 100),
            'grade_average' => fake()->randomFloat(2, 0, 4.0),
            'participation_score' => fake()->numberBetween(0, 100),
            'extracurricular_hours' => fake()->numberBetween(0, 20),
            'counseling_sessions' => fake()->numberBetween(0, 10),
        ];
    }
}
