<?php

namespace App\Filament\Resources\Drivers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DriversTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                
                TextColumn::make('license_number')
                    ->label('Número da Carta')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('license_category')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('availability')
                    ->label('Disponibilidade')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'livre' => 'success',
                        'ocupado' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'livre' => 'Livre',
                        'ocupado' => 'Ocupado',
                        default => $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('availability')
                    ->label('Disponibilidade')
                    ->options([
                        'livre' => 'Livre',
                        'ocupado' => 'Ocupado',
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
