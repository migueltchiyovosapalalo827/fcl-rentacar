<?php

namespace Database\Factories;

use App\Models\Deposit;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deposit>
 */
class DepositFactory extends Factory
{
    protected $model = Deposit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reservation = Reservation::inRandomOrder()->first() ?? Reservation::factory()->create();
        $refunded = fake()->boolean(20); // 20% chance de estar reembolsado

        return [
            'reservation_id' => $reservation->id,
            'amount' => fake()->randomFloat(2, 100, 500),
            'refunded' => $refunded,
            'refunded_at' => $refunded ? fake()->dateTimeBetween('-30 days', 'now') : null,
        ];
    }

    public function reembolsado(): static
    {
        return $this->state(fn (array $attributes) => [
            'refunded' => true,
            'refunded_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    public function naoReembolsado(): static
    {
        return $this->state(fn (array $attributes) => [
            'refunded' => false,
            'refunded_at' => null,
        ]);
    }
}

