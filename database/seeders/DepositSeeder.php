<?php

namespace Database\Seeders;

use App\Models\Deposit;
use Illuminate\Database\Seeder;

class DepositSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar depósitos
        Deposit::factory(20)->naoReembolsado()->create();
        Deposit::factory(5)->reembolsado()->create();
    }
}

