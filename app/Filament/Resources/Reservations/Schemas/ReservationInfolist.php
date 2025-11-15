<?php

namespace App\Filament\Resources\Reservations\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReservationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações da Reserva')
                    ->schema([
                        TextEntry::make('client.name')
                            ->label('Cliente'),
                        
                        TextEntry::make('car.brand')
                            ->label('Marca'),
                        
                        TextEntry::make('car.model')
                            ->label('Modelo'),
                        
                        TextEntry::make('car.plate_number')
                            ->label('Matrícula'),
                        
                        TextEntry::make('start_date')
                            ->label('Data de Início')
                            ->dateTime('d/m/Y H:i'),
                        
                        TextEntry::make('end_date')
                            ->label('Data de Fim')
                            ->dateTime('d/m/Y H:i'),
                        
                        TextEntry::make('status')
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
                        
                        TextEntry::make('total_amount')
                            ->label('Valor Total')
                            ->money('AOA'),
                        
                        IconEntry::make('with_driver')
                            ->label('Com Motorista')
                            ->boolean(),
                        
                        TextEntry::make('driver.license_number')
                            ->label('Motorista')
                            ->placeholder('N/A'),
                        
                        TextEntry::make('pickupLocation.name')
                            ->label('Local de Recolha'),
                        
                        TextEntry::make('dropoffLocation.name')
                            ->label('Local de Devolução'),
                        
                        TextEntry::make('purpose')
                            ->label('Propósito')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'negocios' => 'Negócios',
                                'casamento' => 'Casamento',
                                'passeio' => 'Passeio',
                                'trabalho' => 'Trabalho',
                                'outros' => 'Outros',
                                default => $state,
                            }),
                    ])
                    ->columns(2),
            ]);
    }
}
