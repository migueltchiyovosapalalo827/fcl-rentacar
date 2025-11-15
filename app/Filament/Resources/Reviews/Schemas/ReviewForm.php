<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('reservation_id')
                    ->label('Reserva')
                    ->relationship('reservation', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Reserva #{$record->id} - {$record->client->name}")
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(),
                
                Select::make('user_id')
                    ->label('Cliente')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(),
                
                TextInput::make('rating')
                    ->label('Avaliação')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->required()
                    ->suffix('/5'),
                
                Textarea::make('comment')
                    ->label('Comentário')
                    ->rows(4)
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ]);
    }
}
