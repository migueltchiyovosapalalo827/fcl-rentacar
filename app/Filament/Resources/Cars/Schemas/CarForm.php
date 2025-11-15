<?php

namespace App\Filament\Resources\Cars\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('brand')
                    ->label('Marca')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('model')
                    ->label('Modelo')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('plate_number')
                    ->label('Matrícula')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                
                TextInput::make('price_per_day')
                    ->label('Preço por Dia')
                    ->required()
                    ->numeric()
                    ->prefix('AOA')
                    ->step(0.01),
                
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'disponivel' => 'Disponível',
                        'alugado' => 'Alugado',
                        'manutencao' => 'Em Manutenção',
                        'inativo' => 'Inativo',
                    ])
                    ->required()
                    ->default('disponivel'),
                
                TextInput::make('year')
                    ->label('Ano')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(now()->year),
                
                TextInput::make('km')
                    ->label('Quilometragem')
                    ->numeric()
                    ->default(0)
                    ->minValue(0),
                
                FileUpload::make('image')
                    ->label('Imagem')
                    ->image()
                    ->directory('cars')
                    ->visibility('public')
                    ->maxSize(5120),
            ]);
    }
}
