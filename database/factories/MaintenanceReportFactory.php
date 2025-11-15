<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\MaintenanceReport;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceReport>
 */
class MaintenanceReportFactory extends Factory
{
    protected $model = MaintenanceReport::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $car = Car::inRandomOrder()->first() ?? Car::factory()->create();
        $status = fake()->randomElement(['em_analise', 'em_reparacao', 'concluido']);
        
        $descriptions = [
            'Troca de óleo e filtros',
            'Revisão completa do veículo',
            'Reparo no sistema de freios',
            'Substituição de pneus',
            'Reparo no sistema elétrico',
            'Manutenção preventiva',
            'Reparo na suspensão',
            'Troca de bateria',
            'Reparo no ar condicionado',
            'Inspeção geral e diagnóstico',
        ];

        $costs = [
            'em_analise' => 0,
            'em_reparacao' => fake()->randomFloat(2, 50, 500),
            'concluido' => fake()->randomFloat(2, 100, 1000),
        ];

        return [
            'car_id' => $car->id,
            'technician_id' => User::factory(),
            'reservation_id' => fake()->boolean(40) ? (Reservation::inRandomOrder()->first()?->id ?? null) : null,
            'description' => fake()->randomElement($descriptions),
            'status' => $status,
            'cost' => $costs[$status],
        ];
    }

    public function emAnalise(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'em_analise',
            'cost' => 0,
        ]);
    }

    public function emReparacao(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'em_reparacao',
            'cost' => fake()->randomFloat(2, 50, 500),
        ]);
    }

    public function concluido(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'concluido',
            'cost' => fake()->randomFloat(2, 100, 1000),
        ]);
    }
}

