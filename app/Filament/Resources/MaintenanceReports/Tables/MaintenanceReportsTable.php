<?php

namespace App\Filament\Resources\MaintenanceReports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MaintenanceReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('car.brand')
                    ->label('Marca')
                    ->searchable(),
                
                TextColumn::make('car.model')
                    ->label('Modelo')
                    ->searchable(),
                
                TextColumn::make('car.plate_number')
                    ->label('Matrícula')
                    ->searchable(),
                
                TextColumn::make('technician.name')
                    ->label('Técnico')
                    ->searchable(),
                
                TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(50)
                    ->wrap(),
                
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'em_analise' => 'warning',
                        'em_reparacao' => 'info',
                        'concluido' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'em_analise' => 'Em Análise',
                        'em_reparacao' => 'Em Reparação',
                        'concluido' => 'Concluído',
                        default => $state,
                    }),
                
                TextColumn::make('cost')
                    ->label('Custo')
                    ->money('AOA')
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'em_analise' => 'Em Análise',
                        'em_reparacao' => 'Em Reparação',
                        'concluido' => 'Concluído',
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
