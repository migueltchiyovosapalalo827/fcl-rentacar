<?php

namespace App\Filament\Resources\Deposits\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;

class DepositInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações do Depósito')
                    ->schema([
                        TextEntry::make('amount')
                            ->label('Valor'),
                    ]),
                    Section::make('Informações da Reserva')
                    ->schema([
                        TextEntry::make('reservation.client.name')
                            ->label('Cliente'),
                    ]),
                    
            ]);
    }
}
