@extends('client.layout')

@section('content')
<div class="mb-8">
    <a href="{{ route('client.reservations.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
        ← Voltar para Minhas Reservas
    </a>
    <h1 class="text-3xl font-bold text-gray-900">Detalhes da Reserva #{{ $reservation->id }}</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informações Principais -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informações do Carro -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações do Veículo</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Marca</p>
                    <p class="text-lg font-medium text-gray-900">{{ $reservation->car->brand }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Modelo</p>
                    <p class="text-lg font-medium text-gray-900">{{ $reservation->car->model }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Matrícula</p>
                    <p class="text-lg font-medium text-gray-900">{{ $reservation->car->plate_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Ano</p>
                    <p class="text-lg font-medium text-gray-900">{{ $reservation->car->year ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Datas e Locais -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Datas e Locais</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Data de Início</p>
                    <p class="text-lg font-medium text-gray-900">{{ $reservation->start_date->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Data de Fim</p>
                    <p class="text-lg font-medium text-gray-900">{{ $reservation->end_date->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Local de Recolha</p>
                    <p class="text-lg font-medium text-gray-900">{{ $reservation->pickupLocation->name }}</p>
                    <p class="text-sm text-gray-500">{{ $reservation->pickupLocation->address }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Local de Devolução</p>
                    <p class="text-lg font-medium text-gray-900">{{ $reservation->dropoffLocation->name }}</p>
                    <p class="text-sm text-gray-500">{{ $reservation->dropoffLocation->address }}</p>
                </div>
            </div>
        </div>

        <!-- Motorista -->
        @if($reservation->with_driver && $reservation->driver)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Motorista</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nome</p>
                    <p class="text-lg font-medium text-gray-900">{{ $reservation->driver->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Carta de Condução</p>
                    <p class="text-lg font-medium text-gray-900">{{ $reservation->driver->license_number }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Pagamentos -->
        @if($reservation->payments->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Pagamentos</h2>
            <div class="space-y-3">
                @foreach($reservation->payments as $payment)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <div>
                        <p class="font-medium text-gray-900">{{ ucfirst($payment->type) }}</p>
                        <p class="text-sm text-gray-600">{{ ucfirst($payment->method) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">{{ number_format($payment->amount, 2) }} AOA</p>
                        <span class="px-2 py-1 text-xs rounded
                            @if($payment->status === 'pago') bg-green-100 text-green-800
                            @elseif($payment->status === 'pendente') bg-yellow-100 text-yellow-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status e Ações -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Status</h2>
            <div class="mb-4">
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                    @if($reservation->status === 'pendente') bg-yellow-100 text-yellow-800
                    @elseif($reservation->status === 'confirmada') bg-blue-100 text-blue-800
                    @elseif($reservation->status === 'ativa') bg-green-100 text-green-800
                    @elseif($reservation->status === 'concluida') bg-gray-100 text-gray-800
                    @else bg-red-100 text-red-800
                    @endif">
                    @if($reservation->status === 'pendente') Pendente
                    @elseif($reservation->status === 'confirmada') Confirmada
                    @elseif($reservation->status === 'ativa') Ativa
                    @elseif($reservation->status === 'concluida') Concluída
                    @else Cancelada
                    @endif
                </span>
            </div>
            
            @if(in_array($reservation->status, ['pendente', 'confirmada']))
            <form action="{{ route('client.reservations.cancel', $reservation) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja cancelar esta reserva?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    Cancelar Reserva
                </button>
            </form>
            @endif
        </div>

        <!-- Resumo Financeiro -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Resumo Financeiro</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium">{{ number_format($reservation->total_amount, 2) }} AOA</span>
                </div>
                @if($reservation->deposit)
                <div class="flex justify-between">
                    <span class="text-gray-600">Caução</span>
                    <span class="font-medium">{{ number_format($reservation->deposit->amount, 2) }} AOA</span>
                </div>
                @endif
                <div class="pt-3 border-t border-gray-200">
                    <div class="flex justify-between">
                        <span class="text-lg font-semibold text-gray-900">Total</span>
                        <span class="text-lg font-bold text-blue-600">{{ number_format($reservation->total_amount + ($reservation->deposit->amount ?? 0), 2) }} AOA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações Adicionais -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações</h2>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-600">Propósito</p>
                    <p class="font-medium text-gray-900">{{ ucfirst($reservation->purpose) }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Com Motorista</p>
                    <p class="font-medium text-gray-900">{{ $reservation->with_driver ? 'Sim' : 'Não' }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Criada em</p>
                    <p class="font-medium text-gray-900">{{ $reservation->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Avaliação -->
        @if($reservation->status === 'concluida')
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Avaliação</h2>
            @if($reservation->review)
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-600 mb-1">Sua Avaliação</p>
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-2xl {{ $i <= $reservation->review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                            <span class="ml-2 text-gray-700">({{ $reservation->review->rating }}/5)</span>
                        </div>
                    </div>
                    @if($reservation->review->comment)
                    <div>
                        <p class="text-gray-600 mb-1">Seu Comentário</p>
                        <p class="text-gray-900 bg-gray-50 p-3 rounded">{{ $reservation->review->comment }}</p>
                    </div>
                    @endif
                    <p class="text-sm text-gray-500">Avaliado em {{ $reservation->review->created_at->format('d/m/Y H:i') }}</p>
                    
                    @if($reservation->review->reward)
                    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-green-800 font-semibold">🎉 Recompensa Recebida!</p>
                        <p class="text-green-700 text-sm">{{ $reservation->review->reward->description }}</p>
                        @if(!$reservation->review->reward->used)
                            <p class="text-green-600 text-xs mt-2">Válida até {{ $reservation->review->reward->expires_at->format('d/m/Y') }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            @else
                <p class="text-gray-600 mb-4">Ajude-nos a melhorar! Avalie sua experiência com esta reserva e ganhe recompensas.</p>
                <a href="{{ route('client.reviews.create', $reservation) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Avaliar Reserva
                </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection

