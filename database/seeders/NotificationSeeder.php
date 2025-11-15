<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar notificações
        Notification::factory(30)->naoLida()->create();
        Notification::factory(20)->lida()->create();
    }
}

