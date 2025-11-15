@extends('client.layout')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Minhas Reservas</h1>
    <p class="text-gray-600 mt-2">Acompanhe o status de todas as suas reservas</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carro</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Início</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Fim</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Local Recolha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reservations as $reservation)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $reservation->car->brand }} {{ $reservation->car->model }}</div>
                        <div class="text-sm text-gray-500">{{ $reservation->car->plate_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $reservation->start_date->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $reservation->end_date->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $reservation->pickupLocation->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
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
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ number_format($reservation->total_amount, 2) }} AOA
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('client.reservations.show', $reservation) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                            Ver
                        </a>
                        @if(in_array($reservation->status, ['pendente', 'confirmada']))
                            <form action="{{ route('client.reservations.cancel', $reservation) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja cancelar esta reserva?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Cancelar</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="mt-4 text-lg font-medium">Nenhuma reserva encontrada</p>
                            <p class="mt-2 text-sm">Faça sua primeira reserva agora!</p>
                            <a href="{{ route('public.reservations.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                Nova Reserva
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($reservations->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $reservations->links() }}
    </div>
    @endif
</div>
@endsection

