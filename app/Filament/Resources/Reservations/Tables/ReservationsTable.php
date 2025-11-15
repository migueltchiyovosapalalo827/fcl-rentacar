<?php

namespace App\Filament\Resources\Reservations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('car.brand')
                    ->label('Marca')
                    ->searchable(),
                
                TextColumn::make('car.model')
                    ->label('Modelo')
                    ->searchable(),
                
                TextColumn::make('car.plate_number')
                    ->label('Matrícula')
                    ->searchable(),
                
                TextColumn::make('start_date')
                    ->label('Data Início')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                
                TextColumn::make('end_date')
                    ->label('Data Fim')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendente' => 'warning',
                        'confirmada' => 'info',
                        'ativa' => 'success',
                        'concluida' => 'gray',
                        'cancelada' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pendente' => 'Pendente',
                        'confirmada' => 'Confirmada',
                        'ativa' => 'Ativa',
                        'concluida' => 'Concluída',
                        'cancelada' => 'Cancelada',
                        default => $state,
                    }),
                
                TextColumn::make('total_amount')
                    ->label('Valor Total')
                    ->money('AOA')
                    ->sortable(),
                
                IconColumn::make('with_driver')
                    ->label('Com Motorista')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pendente' => 'Pendente',
                        'confirmada' => 'Confirmada',
                        'ativa' => 'Ativa',
                        'concluida' => 'Concluída',
                        'cancelada' => 'Cancelada',
                    ]),
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
