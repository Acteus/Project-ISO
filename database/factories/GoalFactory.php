<?php

namespace Database\Factories;

use App\Models\Goal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Goal>
 */
class GoalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Goal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $metricTypes = [
            'learner_needs_index',
            'satisfaction_score',
            'success_index',
            'safety_index',
            'wellbeing_index',
            'overall_satisfaction',
            'compliance_score',
            'response_rate',
            'attendance_rate',
            'grade_average',
        ];

        $statuses = ['active', 'achieved', 'expired', 'cancelled'];
        $targetValue = fake()->randomFloat(2, 1.0, 5.0);
        $currentValue = fake()->randomFloat(2, 0.0, $targetValue * 1.2);

        return [
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'metric_type' => fake()->randomElement($metricTypes),
            'target_value' => $targetValue,
            'current_value' => $currentValue,
            'target_date' => fake()->dateTimeBetween('now', '+1 year'),
            'status' => fake()->randomElement($statuses),
            'priority' => fake()->numberBetween(1, 4),
            'progress_history' => [],
            'notes' => fake()->optional()->paragraph(),
        ];
    }

    /**
     * Indicate that the goal is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'target_date' => fake()->dateTimeBetween('+1 month', '+1 year'),
        ]);
    }

    /**
     * Indicate that the goal is achieved.
     */
    public function achieved(): static
    {
        $targetValue = fake()->randomFloat(2, 1.0, 5.0);
        
        return $this->state(fn (array $attributes) => [
            'status' => 'achieved',
            'target_value' => $targetValue,
            'current_value' => $targetValue + fake()->randomFloat(2, 0.1, 0.5),
            'target_date' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Indicate that the goal is overdue.
     */
    public function overdue(): static
    {
        $targetValue = fake()->randomFloat(2, 1.0, 5.0);
        $currentValue = fake()->randomFloat(2, 0.0, $targetValue * 0.8);
        
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'target_value' => $targetValue,
            'current_value' => $currentValue,
            'target_date' => fake()->dateTimeBetween('-6 months', '-1 week'),
        ]);
    }

    /**
     * Indicate that the goal has high priority.
     */
    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 4,
        ]);
    }
}


