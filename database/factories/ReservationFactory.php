<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Driver;
use App\Models\Location;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+30 days');
        $endDate = fake()->dateTimeBetween($startDate, $startDate->format('Y-m-d') . ' +7 days');
        
        $days = (int) $startDate->diff($endDate)->format('%a');
        $car = Car::inRandomOrder()->first() ?? Car::factory()->create();
        $pricePerDay = $car->price_per_day;
        $totalAmount = $pricePerDay * $days;

        $withDriver = fake()->boolean(30); // 30% chance de ter motorista
        $driverId = $withDriver ? (Driver::where('availability', 'livre')->inRandomOrder()->first()?->id ?? Driver::factory()->livre()->create()->id) : null;

        if ($withDriver && $driverId) {
            $totalAmount += 50 * $days; // Adiciona 50 por dia para motorista
        }

        return [
            'client_id' => User::factory(),
            'car_id' => $car->id,
            'driver_id' => $driverId,
            'pickup_location_id' => Location::where('active', true)->inRandomOrder()->first()?->id ?? Location::factory()->ativo()->create()->id,
            'dropoff_location_id' => Location::where('active', true)->inRandomOrder()->first()?->id ?? Location::factory()->ativo()->create()->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'purpose' => fake()->randomElement(['negocios', 'casamento', 'passeio', 'trabalho', 'outros']),
            'with_driver' => $withDriver,
            'status' => fake()->randomElement(['pendente', 'confirmada', 'ativa', 'concluida', 'cancelada']),
            'total_amount' => $totalAmount,
        ];
    }

    public function pendente(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pendente',
        ]);
    }

    public function confirmada(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmada',
        ]);
    }

    public function ativa(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ativa',
        ]);
    }

    public function concluida(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'concluida',
        ]);
    }

    public function cancelada(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelada',
        ]);
    }

    public function comMotorista(): static
    {
        return $this->state(function (array $attributes) {
            $driverId = Driver::where('availability', 'livre')->inRandomOrder()->first()?->id ?? Driver::factory()->livre()->create()->id;
            
            $startDate = $attributes['start_date'] instanceof \DateTime 
                ? $attributes['start_date'] 
                : new \DateTime($attributes['start_date']);
            $endDate = $attributes['end_date'] instanceof \DateTime 
                ? $attributes['end_date'] 
                : new \DateTime($attributes['end_date']);
            
            $days = (int) $startDate->diff($endDate)->days + 1;
            $totalAmount = $attributes['total_amount'] ?? 0;
            $totalAmount += 50 * $days;

            return [
                'with_driver' => true,
                'driver_id' => $driverId,
                'total_amount' => $totalAmount,
            ];
        });
    }
}

