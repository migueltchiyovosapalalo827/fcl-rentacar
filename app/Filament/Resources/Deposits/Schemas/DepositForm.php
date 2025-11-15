<?php

namespace App\Filament\Resources\Deposits\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;

class DepositForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('amount')
                    ->label('Valor')
                    ->required()
                    ->numeric()
                    ->prefix('AOA')
                    ->step(0.01),
                Select::make('reservation_id')
                    ->label('Reserva')
                    ->relationship('reservation', 'id')
                    ->searchable()
                    ->preload()
                    ->required(),
                Textarea::make('description')
                    ->label('Observações')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                Toggle::make('refunded')
                    ->label('Reembolsado')
                    ->default(false)->live(),
                DateTimePicker::make('refunded_at')
                    ->label('Data de Reembolso')
                    ->required()
                    ->native(false)
                    ->timezone('Africa/Luanda')
                    ->visible(fn ($get) => $get('refunded'))
                    ->columnSpanFull(),
            ]);
    }
}
