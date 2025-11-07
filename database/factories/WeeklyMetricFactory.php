<?php

namespace Database\Factories;

use App\Models\WeeklyMetric;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WeeklyMetric>
 */
class WeeklyMetricFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WeeklyMetric::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-1 year', 'now');
        $weekStart = Carbon::parse($date)->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();
        $year = $weekStart->year;
        $weekNumber = $weekStart->week;

        $tracks = ['CSS', 'STEM', 'GAS', 'ABM', 'HUMSS'];
        $genders = ['Male', 'Female', 'Non-binary', 'Prefer not to say'];

        return [
            'week_start_date' => $weekStart,
            'week_end_date' => $weekEnd,
            'year' => $year,
            'week_number' => $weekNumber,
            'total_responses' => fake()->numberBetween(10, 500),
            'new_responses' => fake()->numberBetween(0, 50),
            'learner_needs_index' => fake()->randomFloat(2, 1.0, 5.0),
            'satisfaction_score' => fake()->randomFloat(2, 1.0, 5.0),
            'success_index' => fake()->randomFloat(2, 1.0, 5.0),
            'safety_index' => fake()->randomFloat(2, 1.0, 5.0),
            'wellbeing_index' => fake()->randomFloat(2, 1.0, 5.0),
            'overall_satisfaction' => fake()->randomFloat(2, 1.0, 5.0),
            'compliance_score' => fake()->randomFloat(2, 1.0, 5.0),
            'compliance_percentage' => fake()->randomFloat(2, 0, 100),
            'risk_level' => fake()->randomElement(['Low', 'Medium', 'High']),
            'responses_by_track' => array_combine(
                $tracks,
                array_map(fn() => fake()->numberBetween(0, 100), $tracks)
            ),
            'responses_by_grade' => [
                11 => fake()->numberBetween(0, 200),
                12 => fake()->numberBetween(0, 200),
            ],
            'responses_by_gender' => array_combine(
                $genders,
                array_map(fn() => fake()->numberBetween(0, 150), $genders)
            ),
            'satisfaction_trend' => fake()->randomFloat(2, -0.5, 0.5),
            'compliance_trend' => fake()->randomFloat(2, -0.5, 0.5),
            'response_trend' => fake()->randomFloat(2, -10, 10),
            'target_satisfaction' => fake()->randomFloat(2, 3.5, 5.0),
            'target_compliance' => fake()->randomFloat(2, 80, 100),
            'target_responses' => fake()->numberBetween(50, 300),
            'satisfaction_target_met' => fake()->boolean(70),
            'compliance_target_met' => fake()->boolean(70),
            'response_target_met' => fake()->boolean(70),
            'key_insights' => [
                fake()->sentence(),
                fake()->sentence(),
            ],
            'recommendations' => [
                fake()->sentence(),
                fake()->sentence(),
            ],
        ];
    }

    /**
     * Indicate that all targets were met.
     */
    public function allTargetsMet(): static
    {
        return $this->state(fn (array $attributes) => [
            'satisfaction_target_met' => true,
            'compliance_target_met' => true,
            'response_target_met' => true,
        ]);
    }

    /**
     * Indicate that no targets were met.
     */
    public function noTargetsMet(): static
    {
        return $this->state(fn (array $attributes) => [
            'satisfaction_target_met' => false,
            'compliance_target_met' => false,
            'response_target_met' => false,
        ]);
    }

    /**
     * Create metrics for a specific week.
     */
    public function forWeek(int $year, int $weekNumber): static
    {
        $weekStart = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();

        return $this->state(fn (array $attributes) => [
            'week_start_date' => $weekStart,
            'week_end_date' => $weekEnd,
            'year' => $year,
            'week_number' => $weekNumber,
        ]);
    }
}


