<?php

namespace App\Filament\Resources\Cars\Schemas;

use App\Models\Car;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CarInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Fotos')
                    ->schema([
                        ImageEntry::make('image')
                            ->label('Foto principal')
                            ->disk('public')
                            ->visibility('public')
                            ->height(220)
                            ->defaultImageUrl('/images/placeholder-car.png'),

                        ImageEntry::make('photos')
                            ->label('Galeria')
                            ->disk('public')
                            ->visibility('public')
                            ->height(120),
                    ])
                    ->columns(2),

                Section::make('Identificação')
                    ->schema([
                        TextEntry::make('brand')->label('Marca'),
                        TextEntry::make('model')->label('Modelo'),
                        TextEntry::make('plate_number')->label('Matrícula'),
                        TextEntry::make('year')->label('Ano'),
                        TextEntry::make('color')->label('Cor')->placeholder('—'),
                        TextEntry::make('category')
                            ->label('Categoria')
                            ->formatStateUsing(fn (?string $state): string => $state ? (Car::CATEGORIES[$state] ?? $state) : '—'),
                    ])
                    ->columns(3),

                Section::make('Estado e preço')
                    ->schema([
                        TextEntry::make('status')
                            ->label('Estado')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'disponivel' => 'success',
                                'alugado' => 'warning',
                                'manutencao' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => Car::STATUSES[$state] ?? $state),

                        TextEntry::make('price_per_day')
                            ->label('Preço por dia')
                            ->money('AOA'),

                        TextEntry::make('km')
                            ->label('Quilometragem')
                            ->numeric()
                            ->suffix(' km'),
                    ])
                    ->columns(3),

                Section::make('Capacidades')
                    ->schema([
                        TextEntry::make('seats')->label('Lugares')->placeholder('—'),
                        TextEntry::make('doors')->label('Portas')->placeholder('—'),
                        TextEntry::make('luggage_capacity')->label('Malas')->placeholder('—'),
                        TextEntry::make('fuel_type')
                            ->label('Combustível')
                            ->formatStateUsing(fn (?string $state): string => $state ? (Car::FUEL_TYPES[$state] ?? $state) : '—'),
                        TextEntry::make('transmission')
                            ->label('Transmissão')
                            ->formatStateUsing(fn (?string $state): string => $state ? (Car::TRANSMISSIONS[$state] ?? $state) : '—'),
                        TextEntry::make('air_conditioning')
                            ->label('Ar condicionado')
                            ->badge()
                            ->formatStateUsing(fn ($state): string => $state ? 'Sim' : 'Não')
                            ->color(fn ($state): string => $state ? 'success' : 'gray'),
                    ])
                    ->columns(3),

                Section::make('Descrição')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Descrição')
                            ->placeholder('Sem descrição')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
