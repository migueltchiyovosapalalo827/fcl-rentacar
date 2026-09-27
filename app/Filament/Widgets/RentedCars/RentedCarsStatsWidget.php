<?php

namespace App\Filament\Widgets\RentedCars;

use App\Reports\RentedCarsReportQuery;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\Reactive;

class RentedCarsStatsWidget extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false;

    protected ?string $pollingInterval = null;

    #[Reactive]
    public ?array $pageFilters = null;

    protected function getStats(): array
    {
        [$from, $until] = RentedCarsReportQuery::period($this->pageFilters);

        $occupiedNow = RentedCarsReportQuery::currentlyRentedQuery()->count();
        $periodCount = RentedCarsReportQuery::overlappingRentalsQuery($from, $until)->count();
        $uniqueCars = RentedCarsReportQuery::overlappingRentalsQuery($from, $until)
            ->whereNotNull('car_id')
            ->distinct()
            ->count('car_id');
        $revenue = (float) RentedCarsReportQuery::overlappingRentalsQuery($from, $until)->sum('total_amount');

        return [
            Stat::make('Carros ocupados agora', $occupiedNow)
                ->description('Reservas ativas ou confirmadas a decorrer')
                ->descriptionIcon('heroicon-m-truck')
                ->icon('heroicon-o-truck')
                ->color('warning'),
            Stat::make('Alugueres no período', $periodCount)
                ->description($from->format('d/m/Y').' — '.$until->format('d/m/Y'))
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('info'),
            Stat::make('Viaturas alugadas', $uniqueCars)
                ->description('Viaturas distintas no intervalo')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->icon('heroicon-o-squares-2x2')
                ->color('gray'),
            Stat::make('Receita do período', number_format($revenue, 2, ',', ' ').' AOA')
                ->description('Confirmadas, ativas e concluídas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->icon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }
}
