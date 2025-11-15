<?php

namespace App\Filament\Resources\Drivers\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DriverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Usuário')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),
                        TextInput::make('phone')
                            ->label('Telefone'),
                    ]),
                
                TextInput::make('license_number')
                    ->label('Número da Carta de Condução')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('license_category')
                    ->label('Categoria')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ex: B, C, D'),
                
                Select::make('availability')
                    ->label('Disponibilidade')
                    ->options([
                        'livre' => 'Livre',
                        'ocupado' => 'Ocupado',
                    ])
                    ->default('livre')
                    ->required(),
            ]);
    }
}
