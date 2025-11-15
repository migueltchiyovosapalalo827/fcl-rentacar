<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Reservation $reservation,
        public string $type,
        public string $title,
        public string $message
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Removido 'database' porque estamos usando uma tabela customizada
        // e criando notificações manualmente com Notification::create()
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->title . ' - Reserva #' . $this->reservation->id)
            ->greeting('Olá ' . $notifiable->name . '!')
            ->line($this->message)
            ->line('**Detalhes da Reserva:**')
            ->line('Veículo: ' . $this->reservation->car->brand . ' ' . $this->reservation->car->model)
            ->line('Data de Início: ' . $this->reservation->start_date->format('d/m/Y H:i'))
            ->line('Data de Fim: ' . $this->reservation->end_date->format('d/m/Y H:i'))
            ->line('Valor Total: ' . number_format($this->reservation->total_amount, 2) . ' AOA')
            ->action('Ver Detalhes', route('client.reservations.show', $this->reservation))
            ->line('Obrigado por escolher nossos serviços!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
        ];
    }
}
