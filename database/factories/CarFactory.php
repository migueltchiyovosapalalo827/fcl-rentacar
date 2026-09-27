<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        $brands = ['Toyota', 'Honda', 'BMW', 'Mercedes-Benz', 'Audi', 'Volkswagen', 'Ford', 'Nissan', 'Hyundai', 'Kia'];
        $models = [
            'Toyota' => ['Corolla', 'Camry', 'RAV4', 'Hilux', 'Yaris'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'HR-V', 'City'],
            'BMW' => ['Série 3', 'Série 5', 'X3', 'X5', 'Série 1'],
            'Mercedes-Benz' => ['Classe C', 'Classe E', 'GLC', 'GLE', 'Classe A'],
            'Audi' => ['A3', 'A4', 'A6', 'Q3', 'Q5'],
            'Volkswagen' => ['Golf', 'Passat', 'Tiguan', 'Polo', 'Jetta'],
            'Ford' => ['Focus', 'Fiesta', 'EcoSport', 'Ranger', 'Mustang'],
            'Nissan' => ['Sentra', 'Altima', 'Rogue', 'Pathfinder', 'Versa'],
            'Hyundai' => ['Elantra', 'Sonata', 'Tucson', 'Santa Fe', 'i20'],
            'Kia' => ['Rio', 'Cerato', 'Sportage', 'Sorento', 'Picanto'],
        ];

        $brand = fake()->randomElement($brands);
        $model = fake()->randomElement($models[$brand]);
        $category = fake()->randomElement(array_keys(Car::CATEGORIES));
        $seats = match ($category) {
            'van' => fake()->numberBetween(7, 12),
            'suv', 'pickup' => fake()->numberBetween(5, 7),
            default => fake()->numberBetween(4, 5),
        };

        return [
            'brand' => $brand,
            'model' => $model,
            'plate_number' => strtoupper(fake()->unique()->bothify('??-###-??')),
            'price_per_day' => fake()->randomFloat(2, 50, 500),
            'status' => fake()->randomElement(['disponivel', 'alugado', 'manutencao', 'inativo']),
            'year' => fake()->numberBetween(2015, 2024),
            'km' => fake()->numberBetween(0, 200000),
            'image' => null,
            'description' => fake()->paragraphs(2, true),
            'color' => fake()->randomElement(['Branco', 'Preto', 'Prata', 'Cinzento', 'Azul', 'Vermelho']),
            'category' => $category,
            'seats' => $seats,
            'doors' => fake()->randomElement([3, 4, 5]),
            'luggage_capacity' => fake()->numberBetween(1, 5),
            'fuel_type' => fake()->randomElement(array_keys(Car::FUEL_TYPES)),
            'transmission' => fake()->randomElement(array_keys(Car::TRANSMISSIONS)),
            'air_conditioning' => fake()->boolean(85),
            'photos' => [],
        ];
    }

    public function disponivel(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'disponivel',
        ]);
    }

    public function alugado(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'alugado',
        ]);
    }

    public function emManutencao(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'manutencao',
        ]);
    }
}
