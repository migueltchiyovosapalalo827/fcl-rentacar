<?php

namespace App\Filament\Widgets\RentedCars;

use App\Reports\RentedCarsReportQuery;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Livewire\Attributes\Reactive;

class RentedCarsRankingWidget extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    #[Reactive]
    public ?array $pageFilters = null;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Ranking de viaturas no período')
            ->description('Ordenado pelo número de alugueres no intervalo selecionado.')
            ->records(function (): array {
                [$from, $until] = RentedCarsReportQuery::period($this->pageFilters);

                return RentedCarsReportQuery::ranking(
                    RentedCarsReportQuery::overlappingRentalsQuery($from, $until)->get(),
                )->all();
            })
            ->columns([
                TextColumn::make('vehicle')
                    ->label('Viatura')
                    ->weight('medium')
                    ->description(fn ($record): string => is_array($record) ? (string) ($record['plate'] ?? '—') : '—'),
                TextColumn::make('plate')
                    ->label('Matrícula')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('rentals')
                    ->label('Alugueres')
                    ->alignment(Alignment::Center)
                    ->badge()
                    ->color('info'),
                TextColumn::make('days')
                    ->label('Dias alugados')
                    ->alignment(Alignment::Center),
                TextColumn::make('revenue')
                    ->label('Receita')
                    ->money('AOA')
                    ->alignment(Alignment::End)
                    ->weight('medium'),
            ])
            ->striped()
            ->paginated(false)
            ->emptyStateIcon('heroicon-o-chart-bar')
            ->emptyStateHeading('Sem alugueres no período')
            ->emptyStateDescription('Ajuste as datas do filtro para ver o ranking de viaturas.');
    }
}
