<?php

namespace App\Filament\Resources\Notifications\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class NotificationForm
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
                    ->required(),
                
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),
                
                Textarea::make('message')
                    ->label('Mensagem')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),
                
                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'reserva' => 'Reserva',
                        'pagamento' => 'Pagamento',
                        'alerta' => 'Alerta',
                        'sistema' => 'Sistema',
                    ])
                    ->default('sistema')
                    ->required(),
                
                Toggle::make('is_read')
                    ->label('Lida')
                    ->default(false),
            ]);
    }
}
