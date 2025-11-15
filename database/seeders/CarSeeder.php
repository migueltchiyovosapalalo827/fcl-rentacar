<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar 20 carros disponíveis
        Car::factory(20)->disponivel()->create();
        
        // Criar 5 carros alugados
        Car::factory(5)->alugado()->create();
        
        // Criar 3 carros em manutenção
        Car::factory(3)->emManutencao()->create();
    }
}

