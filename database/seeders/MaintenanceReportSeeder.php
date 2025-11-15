<?php

namespace Database\Seeders;

use App\Models\MaintenanceReport;
use Illuminate\Database\Seeder;

class MaintenanceReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar relatórios de manutenção
        MaintenanceReport::factory(5)->emAnalise()->create();
        MaintenanceReport::factory(3)->emReparacao()->create();
        MaintenanceReport::factory(10)->concluido()->create();
    }
}

