<?php

namespace App\Filament\Resources\MaintenanceReports\Schemas;

use App\Models\Car;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MaintenanceReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('car_id')
                    ->label('Carro')
                    ->relationship('car', 'plate_number')
                    ->getOptionLabelFromRecordUsing(fn (Car $record): string => "{$record->brand} {$record->model} - {$record->plate_number}")
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Select::make('technician_id')
                    ->label('Técnico')
                    ->relationship('technician', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Select::make('reservation_id')
                    ->label('Reserva (Opcional)')
                    ->relationship('reservation', 'id')
                    ->searchable()
                    ->preload(),
                
                Textarea::make('description')
                    ->label('Descrição')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'em_analise' => 'Em Análise',
                        'em_reparacao' => 'Em Reparação',
                        'concluido' => 'Concluído',
                    ])
                    ->default('em_analise')
                    ->required(),
                
                TextInput::make('cost')
                    ->label('Custo')
                    ->numeric()
                    ->prefix('AOA')
                    ->step(0.01)
                    ->default(0),
            ]);
    }
}
