<?php

namespace App\Filament\Resources\Reservations\Schemas;

use App\Models\Car;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Select::make('car_id')
                    ->label('Carro')
                    ->relationship(
                        'car',
                        'plate_number',
                        function ($query, $get) {
                            $query->where(function ($q) use ($get) {
                                $q->where('status', 'disponivel');
                                if ($carId = $get('car_id')) {
                                    $q->orWhere('id', $carId);
                                }
                            });
                        }
                    )
                    ->searchable(['brand', 'model', 'plate_number'])
                    ->getOptionLabelFromRecordUsing(fn (Car $record): string => "{$record->brand} {$record->model} - {$record->plate_number}")
                    ->preload()
                    ->required(),
                
                Toggle::make('with_driver')
                    ->label('Com Motorista')
                    ->default(false)
                    ->live(),
                
                Select::make('driver_id')
                    ->label('Motorista')
                    ->relationship(
                        'driver',
                        'license_number',
                        function ($query, $get) {
                            $query->where(function ($q) use ($get) {
                                $q->where('availability', 'livre');
                                if ($driverId = $get('driver_id')) {
                                    $q->orWhere('id', $driverId);
                                }
                            });
                        }
                    )
                    ->searchable()
                    ->preload()
                    ->visible(fn ($get) => $get('with_driver'))
                    ->required(fn ($get) => $get('with_driver')),
                
                Select::make('pickup_location_id')
                    ->label('Local de Recolha')
                    ->relationship(
                        'pickupLocation',
                        'name',
                        function ($query, $get) {
                            $query->where(function ($q) use ($get) {
                                $q->where('active', true);
                                if ($locationId = $get('pickup_location_id')) {
                                    $q->orWhere('id', $locationId);
                                }
                            });
                        }
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                
                Select::make('dropoff_location_id')
                    ->label('Local de Devolução')
                    ->relationship(
                        'dropoffLocation',
                        'name',
                        function ($query, $get) {
                            $query->where(function ($q) use ($get) {
                                $q->where('active', true);
                                if ($locationId = $get('dropoff_location_id')) {
                                    $q->orWhere('id', $locationId);
                                }
                            });
                        }
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                
                DateTimePicker::make('start_date')
                    ->label('Data de Início')
                    ->required()
                    ->native(false)
                    ->timezone('Africa/Luanda')
                    ->minDate(now()),
                
                DateTimePicker::make('end_date')
                    ->label('Data de Fim')
                    ->required()
                    ->native(false)
                    ->timezone('Africa/Luanda')
                    ->minDate(fn ($get) => $get('start_date') ?? now()),
                
                Select::make('purpose')
                    ->label('Propósito')
                    ->options([
                        'negocios' => 'Negócios',
                        'casamento' => 'Casamento',
                        'passeio' => 'Passeio',
                        'trabalho' => 'Trabalho',
                        'outros' => 'Outros',
                    ])
                    ->default('outros')
                    ->required(),
                
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pendente' => 'Pendente',
                        'confirmada' => 'Confirmada',
                        'ativa' => 'Ativa',
                        'concluida' => 'Concluída',
                        'cancelada' => 'Cancelada',
                    ])
                    ->default('pendente')
                    ->required(),
                
                TextInput::make('total_amount')
                    ->label('Valor Total')
                    ->numeric()
                    ->prefix('AOA')
                    ->step(0.01)
                    ->default(0),
            ]);
    }
}
