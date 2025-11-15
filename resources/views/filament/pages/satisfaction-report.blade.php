<x-filament-panels::page>
    @php
        $stats = $this->getStats();
    @endphp

    <x-filament-panels::header
        title="Relatório de Satisfação do Cliente"
        description="Análise detalhada das avaliações dos clientes"
    />

    <div class="space-y-6">
        <!-- Estatísticas Principais -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Total de Avaliações</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_reviews'] }}</p>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Avaliação Média</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['avg_rating'] }}/5</p>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Taxa de Satisfação</h3>
                <p class="text-3xl font-bold text-green-600">{{ $stats['satisfaction_rate'] }}%</p>
                <p class="text-xs text-gray-500 mt-1">(4+ estrelas)</p>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Avaliações 5 Estrelas</h3>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['ratings'][5] }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ $stats['total_reviews'] > 0 ? round(($stats['ratings'][5] / $stats['total_reviews']) * 100, 1) : 0 }}% do total
                </p>
            </div>
        </div>

        <!-- Distribuição de Avaliações -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Distribuição de Avaliações</h2>
            
            <div class="space-y-4">
                @for($i = 5; $i >= 1; $i--)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center">
                            <span class="text-sm font-medium text-gray-700">{{ $i }} Estrelas</span>
                            <span class="ml-2 text-yellow-400">{{ str_repeat('★', $i) }}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-600">{{ $stats['ratings'][$i] }} avaliações</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ $stats['total_reviews'] > 0 ? round(($stats['ratings'][$i] / $stats['total_reviews']) * 100, 1) : 0 }}%
                            </span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div 
                            class="h-2 rounded-full {{ $i >= 4 ? 'bg-green-500' : ($i >= 3 ? 'bg-yellow-500' : 'bg-red-500') }}"
                            style="width: {{ $stats['total_reviews'] > 0 ? ($stats['ratings'][$i] / $stats['total_reviews']) * 100 : 0 }}%"
                        ></div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Avaliações Recentes -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Avaliações Recentes</h2>
            
            <div class="space-y-4">
                @forelse($stats['recent_reviews'] as $review)
                <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $review->user->name }}</p>
                            <p class="text-sm text-gray-600">
                                Reserva #{{ $review->reservation->id }} - 
                                {{ $review->reservation->car->brand }} {{ $review->reservation->car->model }}
                            </p>
                        </div>
                        <div class="flex items-center">
                            <span class="text-yellow-400">{{ str_repeat('★', $review->rating) }}</span>
                            <span class="ml-2 text-sm text-gray-600">({{ $review->rating }}/5)</span>
                        </div>
                    </div>
                    @if($review->comment)
                    <p class="text-gray-700 mt-2">{{ $review->comment }}</p>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">{{ $review->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">Nenhuma avaliação encontrada.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>
