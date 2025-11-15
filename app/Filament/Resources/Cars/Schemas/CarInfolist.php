<?php

namespace App\Filament\Resources\Cars\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class CarInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações do Veículo')
                    ->schema([
                        TextEntry::make('image')
                            ->label('Imagem')
                            ->url(fn ($record) => $record->image ? Storage::url($record->image) : null)
                            ->openUrlInNewTab(),
                        
                        TextEntry::make('brand')
                            ->label('Marca'),
                        
                        TextEntry::make('model')
                            ->label('Modelo'),
                        
                        TextEntry::make('plate_number')
                            ->label('Matrícula'),
                        
                        TextEntry::make('price_per_day')
                            ->label('Preço por Dia')
                            ->money('AOA'),
                        
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'disponivel' => 'success',
                                'alugado' => 'warning',
                                'manutencao' => 'danger',
                                'inativo' => 'gray',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'disponivel' => 'Disponível',
                                'alugado' => 'Alugado',
                                'manutencao' => 'Em Manutenção',
                                'inativo' => 'Inativo',
                                default => $state,
                            }),
                        
                        TextEntry::make('year')
                            ->label('Ano'),
                        
                        TextEntry::make('km')
                            ->label('Quilometragem')
                            ->numeric(),
                    ])
                    ->columns(2),
            ]);
    }
}
