<?php

namespace App\Filament\Resources\Deposits\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class DepositsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('amount')
                    ->label('Valor')
                    ->money('AOA'),
                TextColumn::make('reservation.client.name')
                    ->label('Cliente'),
                TextColumn::make('refunded')
                    ->label('Reembolsado')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger'),
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i'),
                TextColumn::make('reservation.id')
                    ->label('Reserva')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('reservation.client.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reservation.car.brand')
                    ->label('Marca')
                    ->searchable()
                    ->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
