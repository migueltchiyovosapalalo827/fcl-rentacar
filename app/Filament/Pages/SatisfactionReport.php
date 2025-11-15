<?php

namespace App\Filament\Pages;

use App\Models\ReservationReview;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class SatisfactionReport extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected string $view = 'filament.pages.satisfaction-report';

    protected static ?string $navigationLabel = 'Relatório de Satisfação';

    protected static ?string $title = 'Relatório de Satisfação do Cliente';

    protected static ?int $navigationSort = 10;

    public function getStats(): array
    {
        $totalReviews = ReservationReview::count();
        $avgRating = ReservationReview::avg('rating') ?? 0;
        
        $ratings = [
            5 => ReservationReview::where('rating', 5)->count(),
            4 => ReservationReview::where('rating', 4)->count(),
            3 => ReservationReview::where('rating', 3)->count(),
            2 => ReservationReview::where('rating', 2)->count(),
            1 => ReservationReview::where('rating', 1)->count(),
        ];

        $recentReviews = ReservationReview::with(['user', 'reservation.car'])
            ->latest()
            ->limit(10)
            ->get();

        return [
            'total_reviews' => $totalReviews,
            'avg_rating' => round($avgRating, 2),
            'ratings' => $ratings,
            'recent_reviews' => $recentReviews,
            'satisfaction_rate' => $totalReviews > 0 
                ? round((($ratings[5] + $ratings[4]) / $totalReviews) * 100, 1) 
                : 0,
        ];
    }
}
