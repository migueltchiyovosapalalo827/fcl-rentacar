<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reservation.id')
                    ->label('Reserva')
                    ->sortable(),
                
                TextColumn::make('reservation.client.name')
                    ->label('Cliente')
                    ->searchable(),
                
                TextColumn::make('amount')
                    ->label('Valor')
                    ->money('AOA')
                    ->sortable(),
                
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'aluguer' => 'Aluguer',
                        'caucao' => 'Caução',
                        'multa' => 'Multa',
                        'outros' => 'Outros',
                        default => $state,
                    }),
                
                TextColumn::make('method')
                    ->label('Método')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'numerario' => 'Numerário',
                        'transferencia' => 'Transferência',
                        'pos' => 'POS',
                        'outros' => 'Outros',
                        default => $state,
                    }),
                
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pago' => 'success',
                        'pendente' => 'warning',
                        'reembolsado' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pago' => 'Pago',
                        'pendente' => 'Pendente',
                        'reembolsado' => 'Reembolsado',
                        default => $state,
                    }),
                
                TextColumn::make('paid_at')
                    ->label('Data de Pagamento')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('N/A'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pago' => 'Pago',
                        'pendente' => 'Pendente',
                        'reembolsado' => 'Reembolsado',
                    ]),
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'aluguer' => 'Aluguer',
                        'caucao' => 'Caução',
                        'multa' => 'Multa',
                        'outros' => 'Outros',
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
