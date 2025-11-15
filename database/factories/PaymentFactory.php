<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reservation = Reservation::inRandomOrder()->first() ?? Reservation::factory()->create();
        $type = fake()->randomElement(['aluguer', 'caucao', 'multa', 'outros']);
        
        $amount = match($type) {
            'aluguer' => $reservation->total_amount,
            'caucao' => fake()->randomFloat(2, 100, 500),
            'multa' => fake()->randomFloat(2, 50, 300),
            default => fake()->randomFloat(2, 20, 200),
        };

        $status = fake()->randomElement(['pago', 'pendente', 'reembolsado']);
        $paidAt = $status === 'pago' ? fake()->dateTimeBetween('-30 days', 'now') : null;

        return [
            'reservation_id' => $reservation->id,
            'amount' => $amount,
            'type' => $type,
            'paid_at' => $paidAt,
            'method' => fake()->randomElement(['numerario', 'transferencia', 'pos', 'outros']),
            'status' => $status,
        ];
    }

    public function pago(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pago',
            'paid_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    public function pendente(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pendente',
            'paid_at' => null,
        ]);
    }

    public function reembolsado(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'reembolsado',
            'paid_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }
}

