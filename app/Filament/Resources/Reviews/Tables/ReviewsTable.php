<?php

namespace App\Filament\Resources\Reviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reservation.id')
                    ->label('Reserva')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('rating')
                    ->label('Avaliação')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 5 => 'success',
                        $state >= 4 => 'info',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn (int $state): string => str_repeat('★', $state) . ' (' . $state . '/5)')
                    ->sortable(),
                
                TextColumn::make('comment')
                    ->label('Comentário')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),
                
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('rating')
                    ->label('Avaliação')
                    ->options([
                        5 => '5 Estrelas',
                        4 => '4 Estrelas',
                        3 => '3 Estrelas',
                        2 => '2 Estrelas',
                        1 => '1 Estrela',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
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
