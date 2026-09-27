<?php

namespace App\Filament\Resources\Cars\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Imagem')
                    ->disk('public')
                    ->visibility('public')
                    ->circular()
                    ->defaultImageUrl('/images/placeholder-car.png'),
                
                TextColumn::make('brand')
                    ->label('Marca')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('model')
                    ->label('Modelo')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('plate_number')
                    ->label('Matrícula')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('price_per_day')
                    ->label('Preço/Dia')
                    ->money('AOA')
                    ->sortable(),
                
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'disponivel' => 'success',
                        'alugado' => 'warning',
                        'manutencao' => 'danger',
                        'inativo' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => \App\Models\Car::STATUSES[$state] ?? $state),
                
                TextColumn::make('category')
                    ->label('Categoria')
                    ->formatStateUsing(fn (?string $state): string => $state ? (\App\Models\Car::CATEGORIES[$state] ?? $state) : '—')
                    ->toggleable(),

                TextColumn::make('seats')
                    ->label('Lugares')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('km')
                    ->label('KM')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(\App\Models\Car::STATUSES),
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
