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
        // Generate realistic ratings with a slight positive bias (typical for satisfaction surveys)
        $generateRating = function() {
            return fake()->randomElement([3, 3, 4, 4, 4, 5, 5, 5, 2, 1]);
        };

        $gradeLevel = fake()->randomElement([11, 12]);
        $currentYear = date('Y');
        $academicYear = $currentYear . '-' . ($currentYear + 1);

        return [
            'student_id' => 'CSS-' . $gradeLevel . '-' . fake()->unique()->numerify('####'),
            'track' => 'CSS',
            'grade_level' => $gradeLevel,
            'academic_year' => $academicYear,
            'semester' => fake()->randomElement(['1st', '2nd']),
            'gender' => fake()->randomElement(['Male', 'Female', 'Non-binary', 'Prefer not to say']),

            // Section 1: Learner Needs & Expectations (q1-q3)
            'curriculum_relevance_rating' => $generateRating(), // q1: Curriculum meets goals
            'learning_pace_appropriateness' => $generateRating(), // q2: Learning preferences considered
            'individual_support_availability' => $generateRating(), // q3: Career preparation

            // Section 2: Teaching & Learning Quality (q4-q6)
            'learning_style_accommodation' => $generateRating(), // q4: Teaching methods effective
            'teaching_quality_rating' => $generateRating(), // q5: Instructor expertise
            'learning_environment_rating' => $generateRating(), // q6: Class activities enhance understanding

            // Section 3: Assessments & Outcomes (q7-q9)
            'peer_interaction_satisfaction' => $generateRating(), // q7: Fair assessments
            'extracurricular_satisfaction' => $generateRating(), // q8: Timely feedback
            'academic_progress_rating' => $generateRating(), // q9: Accurate grading

            // Section 4: Support & Resources (q10-q12)
            'skill_development_rating' => $generateRating(), // q10: Learning resources
            'critical_thinking_improvement' => $generateRating(), // q11: Technical support
            'problem_solving_confidence' => $generateRating(), // q12: Academic advisors

            // Section 5: Environment & Inclusivity (q13-q15)
            'physical_safety_rating' => $generateRating(), // q13: Inclusive environment
            'psychological_safety_rating' => $generateRating(), // q14: Comfortable participating
            'bullying_prevention_effectiveness' => $generateRating(), // q15: Classroom supports learning

            // Section 6: Feedback & Responsiveness (q16-q18)
            'emergency_preparedness_rating' => $generateRating(), // q16: Feedback taken seriously
            'mental_health_support_rating' => $generateRating(), // q17: School responds to concerns
            'stress_management_support' => $generateRating(), // q18: Improvements visible

            // Section 7: Overall Satisfaction (q19-q21)
            'physical_health_support' => $generateRating(), // q19: Overall satisfaction with CSS
            'overall_wellbeing_rating' => $generateRating(), // q20: Would recommend program
            'overall_satisfaction' => $generateRating(), // q21: Confident about future

            // Section 8: Demographics & Open-ended (from form)
            'positive_aspects' => null, // Year level captured in grade_level
            'improvement_suggestions' => null, // Gender not stored separately in current schema
            'additional_comments' => fake()->optional(0.7)->randomElement([
                'The CSS program has greatly improved my technical skills.',
                'I appreciate the hands-on approach to learning.',
                'More equipment and updated software would be helpful.',
                'The instructors are very supportive and knowledgeable.',
                'I wish we had more industry exposure and internship opportunities.',
                'The program prepares us well for technical certifications.',
                'More focus on emerging technologies would be beneficial.',
            ]),

            // Consent and Privacy
            'consent_given' => true,
            'ip_address' => fake()->ipv4,

            // Indirect Metrics (optional - typically from admin data, not student survey)
            'attendance_rate' => fake()->randomFloat(1, 75, 100),
            'grade_average' => fake()->randomFloat(2, 2.5, 4.0),
            'participation_score' => fake()->numberBetween(60, 100),
            'extracurricular_hours' => fake()->numberBetween(0, 15),
            'counseling_sessions' => fake()->numberBetween(0, 5),
        ];
    }
}
