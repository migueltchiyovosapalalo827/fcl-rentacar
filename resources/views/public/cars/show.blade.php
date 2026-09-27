@extends('public.layout')

@section('content')
@php
    $gallery = $car->galleryUrls();
    $cover = $gallery[0] ?? null;
    $occupied = $currentRental !== null || $car->status === 'alugado';
    $available = $car->status === 'disponivel' && $currentRental === null;
    $occupancyLabel = $occupied ? 'Ocupado' : ($available ? 'Disponível' : $car->statusLabel());
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('public.cars.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">← Voltar aos carros</a>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-5 gap-8">
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-80 bg-gray-200 flex items-center justify-center">
                    @if($cover)
                        <img id="car-main-photo" src="{{ $cover }}" alt="{{ $car->brand }} {{ $car->model }}" class="h-full w-full object-cover">
                    @else
                        <span class="text-gray-400">Sem fotografia</span>
                    @endif
                </div>

                @if(count($gallery) > 1)
                    <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 p-3 bg-gray-50">
                        @foreach($gallery as $index => $url)
                            <button type="button" class="car-thumb h-16 rounded overflow-hidden border-2 {{ $index === 0 ? 'border-blue-600' : 'border-transparent' }}" data-src="{{ $url }}">
                                <img src="{{ $url }}" alt="Foto {{ $index + 1 }}" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Descrição</h2>
                @if($car->description)
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $car->description }}</p>
                @else
                    <p class="text-gray-500">Ainda não existe uma descrição para esta viatura.</p>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">{{ $car->categoryLabel() ?? 'Viatura' }}</p>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $car->brand }} {{ $car->model }}</h1>
                        <p class="text-gray-600 mt-1">Matrícula {{ $car->plate_number }}</p>
                    </div>
                    <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                        {{ $occupied ? 'bg-amber-100 text-amber-800' : ($available ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700') }}">
                        {{ $occupancyLabel }}
                    </span>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <p class="text-sm text-gray-500">Preço</p>
                    <p class="text-3xl font-bold text-blue-600">{{ number_format($car->price_per_day, 2) }} <span class="text-base font-medium text-gray-500">AOA/dia</span></p>
                </div>

                @if($occupied && $currentRental)
                    <p class="mt-4 text-sm text-amber-700 bg-amber-50 rounded-lg p-3">
                        Esta viatura está ocupada até {{ $currentRental->end_date->format('d/m/Y H:i') }}.
                    </p>
                @elseif($car->status === 'manutencao')
                    <p class="mt-4 text-sm text-red-700 bg-red-50 rounded-lg p-3">
                        Esta viatura encontra-se em manutenção.
                    </p>
                @endif

                <div class="mt-6">
                    @if($available)
                        <a href="{{ route('public.reservations.create', ['car_id' => $car->id]) }}" class="block w-full text-center bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                            Reservar esta viatura
                        </a>
                    @else
                        <button type="button" disabled class="block w-full text-center bg-gray-200 text-gray-500 px-6 py-3 rounded-lg font-semibold cursor-not-allowed">
                            Indisponível para reserva
                        </button>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Capacidades e características</h2>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500">Ano</dt>
                        <dd class="font-semibold text-gray-900">{{ $car->year ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Cor</dt>
                        <dd class="font-semibold text-gray-900">{{ $car->color ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Lugares</dt>
                        <dd class="font-semibold text-gray-900">{{ $car->seats ? $car->seats.' pessoas' : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Portas</dt>
                        <dd class="font-semibold text-gray-900">{{ $car->doors ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Malas</dt>
                        <dd class="font-semibold text-gray-900">{{ $car->luggage_capacity ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Combustível</dt>
                        <dd class="font-semibold text-gray-900">{{ $car->fuelLabel() ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Transmissão</dt>
                        <dd class="font-semibold text-gray-900">{{ $car->transmissionLabel() ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Ar condicionado</dt>
                        <dd class="font-semibold text-gray-900">{{ $car->air_conditioning ? 'Sim' : 'Não' }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-gray-500">Quilometragem</dt>
                        <dd class="font-semibold text-gray-900">{{ number_format($car->km) }} km</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-gray-500">Estado operacional</dt>
                        <dd class="font-semibold text-gray-900">{{ $car->statusLabel() }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.car-thumb').forEach(function (button) {
    button.addEventListener('click', function () {
        var main = document.getElementById('car-main-photo');
        if (main) {
            main.src = this.dataset.src;
        }
        document.querySelectorAll('.car-thumb').forEach(function (el) {
            el.classList.remove('border-blue-600');
            el.classList.add('border-transparent');
        });
        this.classList.add('border-blue-600');
        this.classList.remove('border-transparent');
    });
});
</script>
@endsection
