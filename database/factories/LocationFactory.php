<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locations = [
            ['name' => 'Aeroporto Internacional', 'address' => 'Aeroporto Internacional de Luanda, Luanda', 'lat' => -8.8584, 'lng' => 13.2314],
            ['name' => 'Centro da Cidade', 'address' => 'Avenida 4 de Fevereiro, Luanda', 'lat' => -8.8147, 'lng' => 13.2307],
            ['name' => 'Maianga', 'address' => 'Rua Major Kanhangulo, Maianga, Luanda', 'lat' => -8.8267, 'lng' => 13.2433],
            ['name' => 'Talatona', 'address' => 'Avenida de Talatona, Talatona, Luanda', 'lat' => -8.9167, 'lng' => 13.1833],
            ['name' => 'Belas', 'address' => 'Avenida de Luanda, Belas, Luanda', 'lat' => -8.9000, 'lng' => 13.2000],
            ['name' => 'Kilamba', 'address' => 'Cidade do Kilamba, Luanda', 'lat' => -8.9667, 'lng' => 13.2833],
            ['name' => 'Benfica', 'address' => 'Rua do Benfica, Benfica, Luanda', 'lat' => -8.8500, 'lng' => 13.2500],
            ['name' => 'Cacuaco', 'address' => 'Avenida Principal, Cacuaco, Luanda', 'lat' => -8.7833, 'lng' => 13.3333],
        ];

        $location = fake()->randomElement($locations);

        return [
            'name' => $location['name'],
            'address' => $location['address'],
            'latitude' => $location['lat'] + fake()->randomFloat(6, -0.01, 0.01),
            'longitude' => $location['lng'] + fake()->randomFloat(6, -0.01, 0.01),
            'active' => fake()->boolean(90), // 90% chance de estar ativo
        ];
    }

    public function ativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => true,
        ]);
    }

    public function inativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'active' => false,
        ]);
    }
}

