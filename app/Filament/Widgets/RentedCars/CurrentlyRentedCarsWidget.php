<?php

namespace App\Filament\Widgets\RentedCars;

use App\Filament\Resources\Reservations\ReservationResource;
use App\Models\Reservation;
use App\Reports\RentedCarsReportQuery;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Livewire\Attributes\Reactive;

class CurrentlyRentedCarsWidget extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    #[Reactive]
    public ?array $pageFilters = null;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Carros ocupados neste momento')
            ->description('Viaturas com reserva confirmada ou ativa a decorrer agora.')
            ->query(RentedCarsReportQuery::currentlyRentedQuery()->orderBy('end_date'))
            ->columns([
                TextColumn::make('car.brand')
                    ->label('Viatura')
                    ->formatStateUsing(fn (mixed $state, Reservation $record): string => RentedCarsReportQuery::vehicleLabel($record))
                    ->description(fn (Reservation $record): string => $record->car?->plate_number ?: '—')
                    ->searchable()
                    ->weight('medium'),
                TextColumn::make('car.plate_number')
                    ->label('Matrícula')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                TextColumn::make('client.name')
                    ->label('Cliente')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('start_date')
                    ->label('Início')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Devolução')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (?string $state): string => RentedCarsReportQuery::statusColor($state))
                    ->formatStateUsing(fn (?string $state): string => RentedCarsReportQuery::statusLabel($state)),
            ])
            ->recordUrl(fn (Reservation $record): string => ReservationResource::getUrl('view', ['record' => $record]))
            ->striped()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->emptyStateIcon('heroicon-o-truck')
            ->emptyStateHeading('Nenhum carro ocupado')
            ->emptyStateDescription('Não há viaturas alugadas neste momento.');
    }
}
