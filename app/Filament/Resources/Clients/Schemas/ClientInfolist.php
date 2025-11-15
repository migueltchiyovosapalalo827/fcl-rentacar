<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações do Cliente')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nome'),
                        
                        TextEntry::make('email')
                            ->label('Email'),
                        
                        TextEntry::make('phone')
                            ->label('Telefone')
                            ->placeholder('N/A'),
                        
                        TextEntry::make('address')
                            ->label('Endereço')
                            ->placeholder('N/A'),
                        
                        TextEntry::make('reservations')
                            ->label('Total de Reservas')
                            ->state(fn ($record) => $record->reservations()->count())
                            ->numeric(),
                        
                        TextEntry::make('created_at')
                            ->label('Data de Registro')
                            ->dateTime('d/m/Y H:i'),
                        
                        TextEntry::make('updated_at')
                            ->label('Última Atualização')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2),
            ]);
    }
}

