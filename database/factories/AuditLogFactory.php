<?php

namespace Database\Factories;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuditLog>
 */
class AuditLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AuditLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actions = [
            'submit_survey_response',
            'view_analytics',
            'export_excel',
            'view_dashboard',
            'login',
            'logout',
            'update_settings',
            'create_qr_code',
            'delete_qr_code',
            'view_report',
        ];

        return [
            'user_id' => User::factory(),
            'action' => fake()->randomElement($actions),
            'description' => fake()->sentence(),
            'ip_address' => fake()->ipv4(),
            'old_values' => null,
            'new_values' => null,
        ];
    }

    /**
     * Indicate that the audit log is for an anonymous action.
     */
    public function anonymous(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
            'description' => fake()->sentence() . ' (anonymous)',
        ]);
    }

    /**
     * Indicate that the audit log includes old and new values.
     */
    public function withChanges(array $oldValues = null, array $newValues = null): static
    {
        return $this->state(fn (array $attributes) => [
            'old_values' => $oldValues ?? ['field' => 'old_value'],
            'new_values' => $newValues ?? ['field' => 'new_value'],
        ]);
    }
}

