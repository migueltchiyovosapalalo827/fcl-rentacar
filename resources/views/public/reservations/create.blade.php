@extends('public.layout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if(!auth()->check())
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-800">
                    <strong>Atenção:</strong> Você precisa estar logado para fazer uma reserva. 
                    <a href="{{ route('login') }}" class="underline font-semibold">Faça login</a> ou 
                    <a href="{{ route('register') }}" class="underline font-semibold">registre-se</a> para continuar.
                </p>
            </div>
        </div>
    </div>
    @endif
    
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Fazer Reserva</h1>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 mb-2">Erros encontrados:</h3>
                        <ul class="list-disc list-inside text-sm text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('public.reservations.store') }}" method="POST" id="reservationForm">
            @csrf

            <!-- Seleção de Carro -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Carro</label>
                <select name="car_id" id="car_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Selecione um carro</option>
                    @foreach($cars as $car)
                        <option value="{{ $car->id }}" data-price="{{ $car->price_per_day }}" {{ (old('car_id', $selectedCarId ?? null) == $car->id) ? 'selected' : '' }}>
                            {{ $car->brand }} {{ $car->model }} - {{ $car->plate_number }} ({{ number_format($car->price_per_day, 2) }} AOA/dia)
                        </option>
                    @endforeach
                </select>
                @error('car_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Locais -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Local de Recolha</label>
                    <select name="pickup_location_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Selecione...</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('pickup_location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                        @endforeach
                    </select>
                    @error('pickup_location_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Local de Devolução</label>
                    <select name="dropoff_location_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Selecione...</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('dropoff_location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                        @endforeach
                    </select>
                    @error('dropoff_location_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Datas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data de Início</label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data de Fim</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Propósito -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Propósito</label>
                <select name="purpose" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="outros" {{ old('purpose', 'outros') == 'outros' ? 'selected' : '' }}>Outros</option>
                    <option value="negocios" {{ old('purpose') == 'negocios' ? 'selected' : '' }}>Negócios</option>
                    <option value="casamento" {{ old('purpose') == 'casamento' ? 'selected' : '' }}>Casamento</option>
                    <option value="passeio" {{ old('purpose') == 'passeio' ? 'selected' : '' }}>Passeio</option>
                    <option value="trabalho" {{ old('purpose') == 'trabalho' ? 'selected' : '' }}>Trabalho</option>
                </select>
                @error('purpose')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Com Motorista -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="with_driver" id="with_driver" value="1" {{ old('with_driver') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Preciso de motorista (+50 AOA/dia)</span>
                </label>
            </div>

            <!-- Resumo -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Resumo da Reserva</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Preço por dia:</span>
                        <span id="daily-price">0 AOA</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Número de dias:</span>
                        <span id="days">0</span>
                    </div>
                    <div class="flex justify-between" id="driver-cost" style="display: none;">
                        <span>Custo do motorista:</span>
                        <span id="driver-price">0 AOA</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-300 font-bold text-lg">
                        <span>Total:</span>
                        <span id="total-price">0 AOA</span>
                    </div>
                </div>
            </div>

            <!-- Botão de Submissão -->
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Confirmar Reserva
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const carSelect = document.getElementById('car_id');
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');
    const withDriverCheckbox = document.getElementById('with_driver');
    const driverCostDiv = document.getElementById('driver-cost');
    
    function calculateTotal() {
        const selectedCar = carSelect.options[carSelect.selectedIndex];
        const pricePerDay = parseFloat(selectedCar.dataset.price) || 0;
        
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        if (startDate && endDate && endDate > startDate) {
            const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
            
            let total = pricePerDay * days;
            const driverCost = withDriverCheckbox.checked ? 50 * days : 0;
            total += driverCost;
            
            document.getElementById('daily-price').textContent = pricePerDay.toFixed(2) + ' AOA';
            document.getElementById('days').textContent = days;
            document.getElementById('driver-price').textContent = driverCost.toFixed(2) + ' AOA';
            document.getElementById('total-price').textContent = total.toFixed(2) + ' AOA';
            
            if (withDriverCheckbox.checked) {
                driverCostDiv.style.display = 'flex';
            } else {
                driverCostDiv.style.display = 'none';
            }
        } else {
            document.getElementById('daily-price').textContent = '0 AOA';
            document.getElementById('days').textContent = '0';
            document.getElementById('total-price').textContent = '0 AOA';
        }
    }
    
    carSelect.addEventListener('change', calculateTotal);
    startDateInput.addEventListener('change', calculateTotal);
    endDateInput.addEventListener('change', calculateTotal);
    withDriverCheckbox.addEventListener('change', calculateTotal);
    
    // Set minimum date to today
    const today = new Date().toISOString().slice(0, 16);
    startDateInput.min = today;
    endDateInput.min = today;
    
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
    });
});
</script>
@endsection

