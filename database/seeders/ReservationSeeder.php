<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar reservas com diferentes status
        Reservation::factory(5)->pendente()->create();
        Reservation::factory(8)->confirmada()->create();
        Reservation::factory(3)->ativa()->create();
        Reservation::factory(10)->concluida()->create();
        Reservation::factory(2)->cancelada()->create();
        
        // Criar algumas reservas com motorista
        Reservation::factory(5)->comMotorista()->create();
    }
}

