@extends('client.layout')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Minhas Recompensas</h1>
    <p class="text-gray-600 mt-2">Acompanhe suas recompensas e descontos</p>
</div>

<!-- Estatísticas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total de Recompensas</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_rewards'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Disponíveis</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['available_rewards'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-gray-100 rounded-md p-3">
                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Utilizadas</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['used_rewards'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Lista de Recompensas -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900">Histórico de Recompensas</h2>
    </div>
    
    <div class="divide-y divide-gray-200">
        @forelse($rewards as $reward)
        <div class="p-6 {{ $reward->used ? 'bg-gray-50' : ($reward->expires_at && $reward->expires_at->isPast() ? 'bg-red-50' : 'bg-green-50') }}">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $reward->description }}</h3>
                        @if($reward->used)
                            <span class="ml-3 px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded">Utilizada</span>
                        @elseif($reward->expires_at && $reward->expires_at->isPast())
                            <span class="ml-3 px-2 py-1 text-xs bg-red-200 text-red-700 rounded">Expirada</span>
                        @else
                            <span class="ml-3 px-2 py-1 text-xs bg-green-200 text-green-700 rounded">Disponível</span>
                        @endif
                    </div>
                    
                    @if($reward->type === 'discount')
                        <p class="text-gray-700 mb-2">
                            <span class="font-semibold">Desconto:</span> {{ number_format($reward->value, 0) }}%
                        </p>
                    @endif
                    
                    @if($reward->review && $reward->review->reservation)
                        <p class="text-sm text-gray-600 mb-2">
                            Recebida pela avaliação da Reserva #{{ $reward->review->reservation->id }}
                        </p>
                    @endif
                    
                    @if($reward->expires_at)
                        <p class="text-sm text-gray-500">
                            @if($reward->expires_at->isPast())
                                Expirou em {{ $reward->expires_at->format('d/m/Y') }}
                            @else
                                Válida até {{ $reward->expires_at->format('d/m/Y') }}
                            @endif
                        </p>
                    @endif
                    
                    <p class="text-xs text-gray-400 mt-2">
                        Recebida em {{ $reward->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="mt-4 text-lg font-medium text-gray-900">Nenhuma recompensa ainda</p>
            <p class="mt-2 text-sm text-gray-500">Avalie suas reservas concluídas para ganhar recompensas!</p>
        </div>
        @endforelse
    </div>
    
    @if($rewards->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $rewards->links() }}
    </div>
    @endif
</div>
@endsection

