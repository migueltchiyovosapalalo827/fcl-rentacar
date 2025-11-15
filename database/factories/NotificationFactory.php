<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['reserva', 'pagamento', 'alerta', 'sistema'];
        $type = fake()->randomElement($types);

        $titles = [
            'reserva' => [
                'Reserva Confirmada',
                'Reserva Pendente de Confirmação',
                'Reserva Cancelada',
                'Lembrete de Reserva',
                'Reserva Atualizada',
            ],
            'pagamento' => [
                'Pagamento Recebido',
                'Pagamento Pendente',
                'Reembolso Processado',
                'Pagamento em Atraso',
                'Confirmação de Pagamento',
            ],
            'alerta' => [
                'Atenção: Data de Devolução Próxima',
                'Alerta de Manutenção',
                'Veículo Disponível',
                'Atenção: Documentação Pendente',
            ],
            'sistema' => [
                'Bem-vindo ao Sistema',
                'Atualização do Sistema',
                'Manutenção Programada',
                'Nova Funcionalidade Disponível',
            ],
        ];

        $messages = [
            'reserva' => [
                'Sua reserva foi confirmada com sucesso.',
                'Por favor, confirme sua reserva o mais breve possível.',
                'Sua reserva foi cancelada conforme solicitado.',
                'Lembrete: Sua reserva está agendada para amanhã.',
                'Sua reserva foi atualizada com novas informações.',
            ],
            'pagamento' => [
                'Seu pagamento foi recebido e processado com sucesso.',
                'Por favor, realize o pagamento para confirmar sua reserva.',
                'Seu reembolso foi processado e será creditado em breve.',
                'Atenção: Seu pagamento está em atraso.',
                'Pagamento confirmado. Obrigado!',
            ],
            'alerta' => [
                'Lembrete: A data de devolução do veículo está próxima.',
                'O veículo está em manutenção. Reservas temporariamente indisponíveis.',
                'Boa notícia! O veículo que você aguardava está disponível.',
                'Por favor, complete a documentação necessária para finalizar sua reserva.',
            ],
            'sistema' => [
                'Bem-vindo ao nosso sistema de aluguer de carros!',
                'O sistema foi atualizado com novas funcionalidades.',
                'Manutenção programada para esta noite. O sistema pode ficar indisponível.',
                'Nova funcionalidade disponível: Agende sua reserva online!',
            ],
        ];

        $title = fake()->randomElement($titles[$type]);
        $message = fake()->randomElement($messages[$type]);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'is_read' => fake()->boolean(30), // 30% chance de estar lida
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    public function lida(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => true,
        ]);
    }

    public function naoLida(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => false,
        ]);
    }
}

