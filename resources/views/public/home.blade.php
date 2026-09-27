@extends('public.layout')

@section('content')
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Alugue o Carro Perfeito para Você</h1>
            <p class="text-xl mb-8 text-blue-100">Encontre o veículo ideal para suas necessidades com os melhores preços</p>
            <a href="{{ route('public.reservations.create') }}" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Fazer Reserva Agora
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Por Que Escolher-Nos?</h2>
        <p class="text-gray-600">Oferecemos a melhor experiência em aluguer de carros</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <div class="text-4xl mb-4">🚗</div>
            <h3 class="text-xl font-semibold mb-2">Frota Variada</h3>
            <p class="text-gray-600">Carros modernos e bem mantidos para todas as necessidades</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <div class="text-4xl mb-4">💰</div>
            <h3 class="text-xl font-semibold mb-2">Preços Competitivos</h3>
            <p class="text-gray-600">Os melhores preços do mercado sem comprometer a qualidade</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <div class="text-4xl mb-4">✅</div>
            <h3 class="text-xl font-semibold mb-2">Reserva Fácil</h3>
            <p class="text-gray-600">Processo simples e rápido para fazer sua reserva online</p>
        </div>
    </div>
</div>

<div class="bg-gray-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Carros em Destaque</h2>
            <a href="{{ route('public.cars.index') }}" class="text-blue-600 hover:text-blue-800">Ver Todos os Carros →</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($featuredCars ?? [] as $car)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    @if($car->coverUrl())
                        <img src="{{ $car->coverUrl() }}" alt="{{ $car->brand }} {{ $car->model }}" class="h-full w-full object-cover">
                    @else
                        <span class="text-gray-400">Sem Imagem</span>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">
                        <a href="{{ route('public.cars.show', $car) }}" class="hover:text-blue-600">{{ $car->brand }} {{ $car->model }}</a>
                    </h3>
                    <p class="text-gray-600 mb-4">Matrícula: {{ $car->plate_number }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-blue-600">{{ number_format($car->price_per_day, 2) }} AOA/dia</span>
                        <a href="{{ route('public.reservations.create', ['car_id' => $car->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Reservar
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center text-gray-600">
                <p>Nenhum carro disponível no momento.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

