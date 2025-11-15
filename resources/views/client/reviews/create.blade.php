@extends('client.layout')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('client.reservations.show', $reservation) }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
            ← Voltar para Detalhes da Reserva
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Avaliar Reserva #{{ $reservation->id }}</h1>
        <p class="text-gray-600 mt-2">Sua opinião é muito importante para nós!</p>
    </div>

    <div class="bg-white rounded-lg shadow p-8">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Detalhes da Reserva</h2>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-700"><strong>Carro:</strong> {{ $reservation->car->brand }} {{ $reservation->car->model }}</p>
                <p class="text-gray-700"><strong>Matrícula:</strong> {{ $reservation->car->plate_number }}</p>
                <p class="text-gray-700"><strong>Período:</strong> {{ $reservation->start_date->format('d/m/Y') }} - {{ $reservation->end_date->format('d/m/Y') }}</p>
            </div>
        </div>

        <form action="{{ route('client.reviews.store', $reservation) }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Avaliação <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center space-x-2" id="rating-container">
                    @for($i = 5; $i >= 1; $i--)
                    <input type="radio" name="rating" value="{{ $i }}" id="rating-{{ $i }}" class="hidden rating-input" {{ old('rating') == $i ? 'checked' : ($i == 5 ? 'checked' : '') }} required>
                    <label for="rating-{{ $i }}" class="cursor-pointer text-4xl rating-label {{ old('rating') >= $i ? 'text-yellow-400' : 'text-gray-300' }}" data-rating="{{ $i }}">
                        ★
                    </label>
                    @endfor
                </div>
                @error('rating')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                    Comentário (Opcional)
                </label>
                <textarea 
                    name="comment" 
                    id="comment" 
                    rows="5" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Compartilhe sua experiência..."
                >{{ old('comment') }}</textarea>
                @error('comment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('client.reservations.show', $reservation) }}" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Enviar Avaliação
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingInputs = document.querySelectorAll('.rating-input');
    const ratingLabels = document.querySelectorAll('.rating-label');
    
    ratingLabels.forEach(label => {
        label.addEventListener('click', function() {
            const rating = this.dataset.rating;
            document.getElementById('rating-' + rating).checked = true;
            
            // Atualizar visual das estrelas
            ratingLabels.forEach((l, index) => {
                const labelRating = l.dataset.rating;
                if (labelRating <= rating) {
                    l.classList.remove('text-gray-300');
                    l.classList.add('text-yellow-400');
                } else {
                    l.classList.remove('text-yellow-400');
                    l.classList.add('text-gray-300');
                }
            });
        });
        
        label.addEventListener('mouseenter', function() {
            const rating = this.dataset.rating;
            ratingLabels.forEach((l) => {
                const labelRating = l.dataset.rating;
                if (labelRating <= rating) {
                    l.classList.add('text-yellow-300');
                }
            });
        });
        
        label.addEventListener('mouseleave', function() {
            const checkedRating = document.querySelector('.rating-input:checked')?.value || 5;
            ratingLabels.forEach((l) => {
                const labelRating = l.dataset.rating;
                l.classList.remove('text-yellow-300');
                if (labelRating <= checkedRating) {
                    l.classList.add('text-yellow-400');
                } else {
                    l.classList.add('text-gray-300');
                }
            });
        });
    });
});
</script>
@endsection

