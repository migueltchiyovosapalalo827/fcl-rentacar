<?php

namespace App\Console\Commands;

use App\Mail\ReservationNotificationMail;
use App\Models\Notification;
use App\Models\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReservationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia lembretes para clientes sobre reservas que começam em breve';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Buscar reservas que começam em 24 horas e estão confirmadas ou ativas
        $tomorrow = now()->addDay()->startOfDay();
        $endOfTomorrow = now()->addDay()->endOfDay();

        $reservations = Reservation::whereIn('status', ['confirmada', 'ativa'])
            ->whereBetween('start_date', [$tomorrow, $endOfTomorrow])
            ->with(['client', 'car', 'pickupLocation'])
            ->get();

        $sentCount = 0;

        foreach ($reservations as $reservation) {
            // Verificar se já foi enviado um lembrete hoje
            $existingNotification = Notification::where('user_id', $reservation->client_id)
                ->where('type', 'alerta')
                ->where('title', 'Lembrete de Reserva')
                ->whereDate('created_at', today())
                ->where('message', 'like', "%Reserva #{$reservation->id}%")
                ->exists();

            if (!$existingNotification) {
                $client = $reservation->client;
                if (! $client) {
                    continue;
                }

                $carLabel = trim(($reservation->car?->brand ?? '').' '.($reservation->car?->model ?? '')) ?: 'o veículo';
                $pickupName = $reservation->pickupLocation?->name ?? 'o local de recolha';
                $startTime = optional($reservation->start_date)->format('H:i') ?? '--:--';
                $title = 'Lembrete de Reserva';
                $message = "Lembrete: Sua reserva #{$reservation->id} começa amanhã às {$startTime}. Não se esqueça de retirar o veículo {$carLabel} no local de recolha: {$pickupName}.";

                // Criar notificação no banco de dados
                Notification::create([
                    'user_id' => $client->id,
                    'title' => $title,
                    'message' => $message,
                    'type' => 'alerta',
                    'is_read' => false,
                    'created_at' => now(),
                ]);

                if ($client?->email) {
                    try {
                        Mail::to($client->email)->send(
                            new ReservationNotificationMail(
                                $reservation,
                                'alerta',
                                $title,
                                $message
                            )
                        );
                    } catch (\Exception $e) {
                        Log::error('Failed to send reminder email: '.$e->getMessage());
                    }
                }

                $sentCount++;
            }
        }

        $this->info("Enviados {$sentCount} lembretes de reserva.");

        return Command::SUCCESS;
    }
}
