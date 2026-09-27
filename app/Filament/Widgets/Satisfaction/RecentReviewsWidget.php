<?php

namespace App\Filament\Widgets\Satisfaction;

use App\Models\ReservationReview;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentReviewsWidget extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Avaliações recentes')
            ->description('As últimas opiniões deixadas pelos clientes.')
            ->query(
                ReservationReview::query()
                    ->with(['user', 'reservation.car'])
                    ->latest(),
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable()
                    ->weight('medium')
                    ->placeholder('—'),
                TextColumn::make('reservation.id')
                    ->label('Reserva')
                    ->formatStateUsing(function (mixed $state, ReservationReview $record): string {
                        $car = $record->reservation?->car;
                        $vehicle = $car
                            ? trim(($car->brand ?? '').' '.($car->model ?? ''))
                            : '';

                        return $vehicle !== ''
                            ? "#{$state} · {$vehicle}"
                            : '#'.$state;
                    }),
                TextColumn::make('rating')
                    ->label('Avaliação')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 5 => 'success',
                        $state >= 4 => 'info',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn (int $state): string => str_repeat('★', $state).' ('.$state.'/5)'),
                TextColumn::make('comment')
                    ->label('Comentário')
                    ->wrap()
                    ->limit(80)
                    ->placeholder('Sem comentário'),
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->striped()
            ->paginated([10, 25])
            ->defaultPaginationPageOption(10)
            ->emptyStateIcon('heroicon-o-star')
            ->emptyStateHeading('Nenhuma avaliação encontrada')
            ->emptyStateDescription('Ainda não existem avaliações de clientes.');
    }
}
