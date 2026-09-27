<?php

namespace App\Filament\Widgets\RentedCars;

use App\Filament\Resources\Reservations\ReservationResource;
use App\Models\Reservation;
use App\Reports\RentedCarsReportQuery;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Livewire\Attributes\Reactive;

class RentedCarsDetailWidget extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    #[Reactive]
    public ?array $pageFilters = null;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Detalhe dos alugueres')
            ->description('Reservas confirmadas, ativas e concluídas que se sobrepõem ao período.')
            ->query(function () {
                [$from, $until] = RentedCarsReportQuery::period($this->pageFilters);

                return RentedCarsReportQuery::overlappingRentalsQuery($from, $until)
                    ->orderByDesc('start_date');
            })
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('car.brand')
                    ->label('Viatura')
                    ->formatStateUsing(fn (mixed $state, Reservation $record): string => RentedCarsReportQuery::vehicleLabel($record))
                    ->description(fn (Reservation $record): string => $record->car?->plate_number ?: '—')
                    ->searchable()
                    ->weight('medium'),
                TextColumn::make('client.name')
                    ->label('Cliente')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('start_date')
                    ->label('Período')
                    ->formatStateUsing(function (mixed $state, Reservation $record): string {
                        $start = optional($record->start_date)->format('d/m/Y');
                        $end = optional($record->end_date)->format('d/m/Y');

                        return "{$start} → {$end}";
                    })
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (?string $state): string => RentedCarsReportQuery::statusColor($state))
                    ->formatStateUsing(fn (?string $state): string => RentedCarsReportQuery::statusLabel($state)),
                TextColumn::make('total_amount')
                    ->label('Valor')
                    ->money('AOA')
                    ->sortable()
                    ->alignment(Alignment::End)
                    ->weight('medium'),
            ])
            ->recordUrl(fn (Reservation $record): string => ReservationResource::getUrl('view', ['record' => $record]))
            ->striped()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->emptyStateHeading('Nenhum aluguer encontrado')
            ->emptyStateDescription('Não existem alugueres neste intervalo de datas.');
    }
}
