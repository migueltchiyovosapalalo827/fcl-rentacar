<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    protected $model = Driver::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['B', 'C', 'D', 'BE', 'CE', 'DE'];

        return [
            'user_id' => User::factory(),
            'license_number' => strtoupper(fake()->unique()->bothify('??#######')),
            'license_category' => fake()->randomElement($categories),
            'availability' => fake()->randomElement(['livre', 'ocupado']),
        ];
    }

    public function livre(): static
    {
        return $this->state(fn (array $attributes) => [
            'availability' => 'livre',
        ]);
    }

    public function ocupado(): static
    {
        return $this->state(fn (array $attributes) => [
            'availability' => 'ocupado',
        ]);
    }
}

