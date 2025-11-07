<?php

namespace Database\Factories;

use App\Models\QrCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QrCode>
 */
class QrCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QrCode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currentYear = date('Y');
        $academicYear = $currentYear . '-' . ($currentYear + 1);
        
        $formats = ['png', 'svg'];
        $tracks = ['CSS', 'STEM', 'GAS', 'ABM', 'HUMSS'];
        $sections = ['A', 'B', 'C', 'D'];

        return [
            'name' => fake()->words(3, true) . ' QR Code',
            'description' => fake()->sentence(),
            'target_url' => fake()->url(),
            'file_path' => 'qr-codes/' . fake()->uuid() . '.png',
            'format' => fake()->randomElement($formats),
            'size' => fake()->randomElement([200, 300, 400, 500]),
            'foreground_color' => fake()->hexColor(),
            'background_color' => '#FFFFFF',
            'track' => fake()->randomElement($tracks),
            'grade_level' => fake()->randomElement([11, 12]),
            'section' => fake()->randomElement($sections),
            'academic_year' => $academicYear,
            'semester' => fake()->randomElement(['1st', '2nd']),
            'scan_count' => fake()->numberBetween(0, 1000),
            'scan_analytics' => [],
            'version' => 1,
            'expires_at' => fake()->optional()->dateTimeBetween('+1 month', '+1 year'),
            'is_active' => true,
            'custom_options' => [],
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the QR code is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the QR code is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the QR code is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => fake()->dateTimeBetween('-1 year', '-1 week'),
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the QR code has been scanned multiple times.
     */
    public function scanned(int $count = null): static
    {
        $scanCount = $count ?? fake()->numberBetween(10, 1000);
        $analytics = [];
        
        for ($i = 0; $i < min($scanCount, 100); $i++) {
            $analytics[] = [
                'timestamp' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d H:i:s'),
                'user_agent' => fake()->userAgent(),
                'ip_address' => fake()->ipv4(),
            ];
        }

        return $this->state(fn (array $attributes) => [
            'scan_count' => $scanCount,
            'scan_analytics' => $analytics,
        ]);
    }
}

