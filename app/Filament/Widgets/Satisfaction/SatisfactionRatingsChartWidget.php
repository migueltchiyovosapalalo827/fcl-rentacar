<?php

namespace App\Filament\Widgets\Satisfaction;

use App\Models\ReservationReview;
use Filament\Widgets\ChartWidget;

class SatisfactionRatingsChartWidget extends ChartWidget
{
    protected static bool $isDiscovered = false;

    protected ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Distribuição de avaliações';

    protected ?string $description = 'Quantidade de avaliações recebidas por classificação.';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $counts = [];

        for ($rating = 5; $rating >= 1; $rating--) {
            $counts[] = ReservationReview::query()->where('rating', $rating)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Avaliações',
                    'data' => $counts,
                    'backgroundColor' => ['#22c55e', '#84cc16', '#eab308', '#f97316', '#ef4444'],
                    'borderWidth' => 0,
                    'borderRadius' => 8,
                ],
            ],
            'labels' => ['5 estrelas', '4 estrelas', '3 estrelas', '2 estrelas', '1 estrela'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                    'grid' => [
                        'color' => 'rgba(128, 128, 128, 0.12)',
                    ],
                ],
            ],
        ];
    }
}
