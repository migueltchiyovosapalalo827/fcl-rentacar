<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('address')
                    ->label('Endereço')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('latitude')
                    ->label('Latitude')
                    ->numeric()
                    ->step(0.00000001),
                
                TextInput::make('longitude')
                    ->label('Longitude')
                    ->numeric()
                    ->step(0.00000001),
                
                Toggle::make('active')
                    ->label('Ativo')
                    ->default(true),
            ]);
    }
}
