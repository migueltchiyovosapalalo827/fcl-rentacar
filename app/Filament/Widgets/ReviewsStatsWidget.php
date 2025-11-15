<?php

namespace App\Filament\Widgets;

use App\Models\ReservationReview;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReviewsStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalReviews = ReservationReview::count();
        $avgRating = ReservationReview::avg('rating') ?? 0;
        $fiveStarReviews = ReservationReview::where('rating', 5)->count();
        $fourStarPlusReviews = ReservationReview::where('rating', '>=', 4)->count();

        return [
            Stat::make('Total de Avaliações', $totalReviews)
                ->description('Todas as avaliações recebidas')
                ->descriptionIcon('heroicon-m-star')
                ->color('success'),
            
            Stat::make('Avaliação Média', number_format($avgRating, 1) . '/5')
                ->description('Média de todas as avaliações')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
            
            Stat::make('Avaliações 5 Estrelas', $fiveStarReviews)
                ->description(number_format($totalReviews > 0 ? ($fiveStarReviews / $totalReviews) * 100 : 0, 1) . '% do total')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
            
            Stat::make('Avaliações 4+ Estrelas', $fourStarPlusReviews)
                ->description(number_format($totalReviews > 0 ? ($fourStarPlusReviews / $totalReviews) * 100 : 0, 1) . '% do total')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
