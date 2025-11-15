@extends('public.layout')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Reserva Confirmada!</h1>
        <p class="text-gray-600 mb-8">Sua reserva foi criada com sucesso. Detalhes abaixo:</p>
        
        <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="font-semibold">Número da Reserva:</span>
                    <span>#{{ $reservation->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold">Carro:</span>
                    <span>{{ $reservation->car->brand }} {{ $reservation->car->model }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold">Data de Início:</span>
                    <span>{{ $reservation->start_date->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold">Data de Fim:</span>
                    <span>{{ $reservation->end_date->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-semibold">Valor Total:</span>
                    <span class="text-xl font-bold text-blue-600">{{ number_format($reservation->total_amount, 2) }} AOA</span>
                </div>
            </div>
        </div>
        
        <div class="flex justify-center space-x-4">
            <a href="{{ route('public.home') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Voltar ao Início
            </a>
            @auth
                <a href="{{ route('client.reservations.show', $reservation) }}" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Ver Detalhes da Reserva
                </a>
                <a href="{{ route('client.dashboard') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                    Área do Cliente
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection

