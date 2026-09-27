@extends('public.layout')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">A nossa frota</h1>
    <p class="text-gray-600 mb-8">Consulte fotos, capacidades, estado e preço de cada viatura.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($cars as $car)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition flex flex-col">
            <div class="h-48 bg-gray-200 flex items-center justify-center relative">
                @if($car->coverUrl())
                    <img src="{{ $car->coverUrl() }}" alt="{{ $car->brand }} {{ $car->model }}" class="h-full w-full object-cover">
                @else
                    <span class="text-gray-400">Sem Imagem</span>
                @endif
                <span class="absolute top-3 right-3 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                    {{ $car->status === 'disponivel' ? 'bg-green-100 text-green-800' : ($car->status === 'alugado' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-700') }}">
                    {{ $car->statusLabel() }}
                </span>
            </div>
            <div class="p-6 flex-1 flex flex-col">
                <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">{{ $car->categoryLabel() }}</p>
                <h3 class="text-xl font-semibold mb-1">
                    <a href="{{ route('public.cars.show', $car) }}" class="hover:text-blue-600">{{ $car->brand }} {{ $car->model }}</a>
                </h3>
                <p class="text-gray-600 text-sm mb-3">{{ $car->year ?? 'Ano n/d' }} · {{ $car->seats ? $car->seats.' lugares' : 'Capacidade n/d' }} · {{ $car->transmissionLabel() ?? 'Transmissão n/d' }}</p>
                <p class="text-2xl font-bold text-blue-600 mb-4">{{ number_format($car->price_per_day, 2) }} <span class="text-sm font-medium text-gray-500">AOA/dia</span></p>
                <div class="mt-auto flex gap-2">
                    <a href="{{ route('public.cars.show', $car) }}" class="flex-1 text-center border border-blue-600 text-blue-600 px-4 py-2 rounded hover:bg-blue-50 font-medium">
                        Ver detalhes
                    </a>
                    @if($car->status === 'disponivel')
                        <a href="{{ route('public.reservations.create', ['car_id' => $car->id]) }}" class="flex-1 text-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-medium">
                            Reservar
                        </a>
                    @endif
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
