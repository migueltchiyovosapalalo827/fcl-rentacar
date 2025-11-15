<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Aeroporto Internacional',
                'address' => 'Aeroporto Internacional de Luanda, Luanda',
                'latitude' => -8.8584,
                'longitude' => 13.2314,
                'active' => true,
            ],
            [
                'name' => 'Centro da Cidade',
                'address' => 'Avenida 4 de Fevereiro, Luanda',
                'latitude' => -8.8147,
                'longitude' => 13.2307,
                'active' => true,
            ],
            [
                'name' => 'Maianga',
                'address' => 'Rua Major Kanhangulo, Maianga, Luanda',
                'latitude' => -8.8267,
                'longitude' => 13.2433,
                'active' => true,
            ],
            [
                'name' => 'Talatona',
                'address' => 'Avenida de Talatona, Talatona, Luanda',
                'latitude' => -8.9167,
                'longitude' => 13.1833,
                'active' => true,
            ],
            [
                'name' => 'Belas',
                'address' => 'Avenida de Luanda, Belas, Luanda',
                'latitude' => -8.9000,
                'longitude' => 13.2000,
                'active' => true,
            ],
            [
                'name' => 'Kilamba',
                'address' => 'Cidade do Kilamba, Luanda',
                'latitude' => -8.9667,
                'longitude' => 13.2833,
                'active' => true,
            ],
            [
                'name' => 'Benfica',
                'address' => 'Rua do Benfica, Benfica, Luanda',
                'latitude' => -8.8500,
                'longitude' => 13.2500,
                'active' => true,
            ],
            [
                'name' => 'Cacuaco',
                'address' => 'Avenida Principal, Cacuaco, Luanda',
                'latitude' => -8.7833,
                'longitude' => 13.3333,
                'active' => true,
            ],
        ];

        foreach ($locations as $location) {
            Location::firstOrCreate(
                ['name' => $location['name']],
                $location
            );
        }
    }
}

