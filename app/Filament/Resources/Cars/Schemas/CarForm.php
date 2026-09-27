<?php

namespace App\Filament\Resources\Cars\Schemas;

use App\Models\Car;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificação')
                    ->schema([
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

                        TextInput::make('year')
                            ->label('Ano')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(now()->year),

                        TextInput::make('color')
                            ->label('Cor')
                            ->maxLength(50),

                        Select::make('category')
                            ->label('Categoria')
                            ->options(Car::CATEGORIES),
                    ])
                    ->columns(2),

                Section::make('Estado e preço')
                    ->schema([
                        Select::make('status')
                            ->label('Estado')
                            ->options(Car::STATUSES)
                            ->required()
                            ->default('disponivel'),

                        TextInput::make('price_per_day')
                            ->label('Preço por dia')
                            ->required()
                            ->numeric()
                            ->prefix('AOA')
                            ->step(0.01),

                        TextInput::make('km')
                            ->label('Quilometragem')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ])
                    ->columns(3),

                Section::make('Capacidades')
                    ->schema([
                        TextInput::make('seats')
                            ->label('Lugares')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(50),

                        TextInput::make('doors')
                            ->label('Portas')
                            ->numeric()
                            ->minValue(2)
                            ->maxValue(6),

                        TextInput::make('luggage_capacity')
                            ->label('Malas')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(20)
                            ->helperText('Número aproximado de malas'),

                        Select::make('fuel_type')
                            ->label('Combustível')
                            ->options(Car::FUEL_TYPES),

                        Select::make('transmission')
                            ->label('Transmissão')
                            ->options(Car::TRANSMISSIONS),

                        Toggle::make('air_conditioning')
                            ->label('Ar condicionado')
                            ->default(true),
                    ])
                    ->columns(3),

                Section::make('Descrição')
                    ->schema([
                        Textarea::make('description')
                            ->label('Descrição')
                            ->rows(4)
                            ->maxLength(2000)
                            ->columnSpanFull(),
                    ]),

                Section::make('Fotos')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Foto principal')
                            ->image()
                            ->disk('public')
                            ->directory('cars')
                            ->visibility('public')
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->fetchFileInformation(false)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->maxSize(5120),

                        FileUpload::make('photos')
                            ->label('Galeria')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->disk('public')
                            ->directory('cars')
                            ->visibility('public')
                            ->openable()
                            ->downloadable()
                            ->fetchFileInformation(false)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->maxFiles(8)
                            ->maxSize(5120),
                    ])
                    ->columns(2),
            ]);
    }
}
