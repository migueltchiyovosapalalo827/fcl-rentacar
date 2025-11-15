<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Reservation;
use App\Models\ReservationReview;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(Reservation $reservation)
    {
        // Verificar se a reserva pertence ao usuário autenticado
        if ($reservation->client_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para avaliar esta reserva.');
        }

        // Verificar se a reserva está concluída
        if ($reservation->status !== 'concluida') {
            return redirect()->route('client.reservations.show', $reservation)
                ->with('error', 'Você só pode avaliar reservas concluídas.');
        }

        // Verificar se já existe uma avaliação
        if ($reservation->review) {
            return redirect()->route('client.reservations.show', $reservation)
                ->with('info', 'Você já avaliou esta reserva.');
        }

        return view('client.reviews.create', compact('reservation'));
    }

    public function store(Request $request, Reservation $reservation)
    {
        // Verificar se a reserva pertence ao usuário autenticado
        if ($reservation->client_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para avaliar esta reserva.');
        }

        // Verificar se a reserva está concluída
        if ($reservation->status !== 'concluida') {
            return redirect()->route('client.reservations.show', $reservation)
                ->with('error', 'Você só pode avaliar reservas concluídas.');
        }

        // Verificar se já existe uma avaliação
        if ($reservation->review) {
            return redirect()->route('client.reservations.show', $reservation)
                ->with('info', 'Você já avaliou esta reserva.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = ReservationReview::create([
            'reservation_id' => $reservation->id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        // Sistema de recompensas baseado na avaliação
        $this->grantReward($review, Auth::user());

        // Criar notificação de agradecimento
        Notification::create([
            'user_id' => Auth::id(),
            'title' => 'Obrigado pela Avaliação',
            'message' => "Obrigado por avaliar sua reserva #{$reservation->id}. Sua opinião é muito importante para nós!" . ($review->rating >= 4 ? ' Você recebeu uma recompensa!' : ''),
            'type' => 'sistema',
            'is_read' => false,
            'created_at' => now(),
        ]);

        return redirect()->route('client.reservations.show', $reservation)
            ->with('success', 'Avaliação enviada com sucesso! Obrigado pelo seu feedback.');
    }

    /**
     * Conceder recompensa baseada na avaliação
     */
    protected function grantReward(ReservationReview $review, $user): void
    {
        // Recompensas baseadas na avaliação
        if ($review->rating >= 5) {
            // 5 estrelas: 10% de desconto na próxima reserva
            Reward::create([
                'user_id' => $user->id,
                'reservation_review_id' => $review->id,
                'type' => 'discount',
                'description' => 'Desconto de 10% na próxima reserva (Avaliação 5 estrelas)',
                'value' => 10.00,
                'used' => false,
                'expires_at' => now()->addMonths(3),
            ]);
        } elseif ($review->rating >= 4) {
            // 4 estrelas: 5% de desconto na próxima reserva
            Reward::create([
                'user_id' => $user->id,
                'reservation_review_id' => $review->id,
                'type' => 'discount',
                'description' => 'Desconto de 5% na próxima reserva (Avaliação 4 estrelas)',
                'value' => 5.00,
                'used' => false,
                'expires_at' => now()->addMonths(2),
            ]);
        }
    }
}
