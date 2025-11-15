<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('phone')
                    ->label('Telefone')
                    ->searchable(),
                
                TextColumn::make('role')
                    ->label('Função')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'gerente' => 'danger',
                        'caixa' => 'info',
                        'motorista' => 'warning',
                        'tecnico' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'gerente' => 'Gerente',
                        'caixa' => 'Caixa',
                        'motorista' => 'Motorista',
                        'tecnico' => 'Técnico',
                        default => $state,
                    })
                    ->sortable(),
                
                TextColumn::make('address')
                    ->label('Endereço')
                    ->limit(30)
                    ->wrap(),
                
                TextColumn::make('created_at')
                    ->label('Data de Criação')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Função')
                    ->options([
                        'gerente' => 'Gerente',
                        'caixa' => 'Caixa',
                        'motorista' => 'Motorista',
                        'tecnico' => 'Técnico',
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

