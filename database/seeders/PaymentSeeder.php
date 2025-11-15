<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar pagamentos com diferentes status
        Payment::factory(15)->pago()->create();
        Payment::factory(10)->pendente()->create();
        Payment::factory(2)->reembolsado()->create();
    }
}

