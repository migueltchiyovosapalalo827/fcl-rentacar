@extends('public.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Carros Disponíveis</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($cars as $car)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="h-48 bg-gray-200 flex items-center justify-center">
                @if($car->image)
                    <img src="{{ Storage::url($car->image) }}" alt="{{ $car->brand }} {{ $car->model }}" class="h-full w-full object-cover">
                @else
                    <span class="text-gray-400">Sem Imagem</span>
                @endif
            </div>
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-2">{{ $car->brand }} {{ $car->model }}</h3>
                <p class="text-gray-600 mb-2">Matrícula: {{ $car->plate_number }}</p>
                <p class="text-gray-600 mb-2">Ano: {{ $car->year ?? 'N/A' }}</p>
                <p class="text-gray-600 mb-4">KM: {{ number_format($car->km) }}</p>
                <div class="flex justify-between items-center">
                    <span class="text-2xl font-bold text-blue-600">{{ number_format($car->price_per_day, 2) }} AOA/dia</span>
                    <a href="{{ route('public.reservations.create', ['car_id' => $car->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Reservar
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center text-gray-600 py-12">
            <p class="text-xl">Nenhum carro disponível no momento.</p>
        </div>
        @endforelse
    </div>
    
    <div class="mt-8">
        {{ $cars->links() }}
    </div>
</div>
@endsection

