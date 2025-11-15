@extends('client.layout')

@section('content')
@if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Notificações</h1>
        <p class="text-gray-600 mt-2">Mantenha-se atualizado sobre suas reservas</p>
    </div>
    @if($unreadCount > 0)
    <form action="{{ route('client.notifications.mark-all-read') }}" method="POST">
        @csrf
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Marcar Todas como Lidas
        </button>
    </form>
    @endif
</div>

<div class="bg-white rounded-lg shadow">
    @forelse($notifications as $notification)
    <div class="border-b border-gray-200 p-6 hover:bg-gray-50 {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <div class="flex items-center mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $notification->title }}</h3>
                    @if(!$notification->is_read)
                        <span class="ml-2 bg-blue-500 text-white text-xs rounded-full px-2 py-1">Nova</span>
                    @endif
                    <span class="ml-3 px-2 py-1 text-xs rounded
                        @if($notification->type === 'reserva') bg-blue-100 text-blue-800
                        @elseif($notification->type === 'pagamento') bg-green-100 text-green-800
                        @elseif($notification->type === 'alerta') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($notification->type) }}
                    </span>
                </div>
                <p class="text-gray-700 mb-2">{{ $notification->message }}</p>
                <p class="text-sm text-gray-500">{{ $notification->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="ml-4 flex space-x-2">
                @if(!$notification->is_read)
                <form action="{{ route('client.notifications.mark-read', $notification) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">
                        Marcar como lida
                    </button>
                </form>
                @endif
                <form action="{{ route('client.notifications.destroy', $notification) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover esta notificação?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                        Remover
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 00-2-2H9a2 2 0 00-2 2v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        <p class="mt-4 text-lg font-medium text-gray-900">Nenhuma notificação</p>
        <p class="mt-2 text-sm text-gray-500">Você não tem notificações no momento.</p>
    </div>
    @endforelse
    
    @if($notifications->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection

