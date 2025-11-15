<?php

namespace App\Filament\Resources\Notifications\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NotificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Usuário')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->wrap(),
                
                TextColumn::make('message')
                    ->label('Mensagem')
                    ->limit(50)
                    ->wrap(),
                
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'reserva' => 'info',
                        'pagamento' => 'success',
                        'alerta' => 'warning',
                        'sistema' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'reserva' => 'Reserva',
                        'pagamento' => 'Pagamento',
                        'alerta' => 'Alerta',
                        'sistema' => 'Sistema',
                        default => $state,
                    }),
                
                IconColumn::make('is_read')
                    ->label('Lida')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'reserva' => 'Reserva',
                        'pagamento' => 'Pagamento',
                        'alerta' => 'Alerta',
                        'sistema' => 'Sistema',
                    ]),
                SelectFilter::make('is_read')
                    ->label('Status')
                    ->options([
                        1 => 'Lida',
                        0 => 'Não Lida',
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
