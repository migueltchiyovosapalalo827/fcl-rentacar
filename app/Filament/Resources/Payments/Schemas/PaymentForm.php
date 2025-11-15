<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
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
                    ->required(),
                
                TextInput::make('amount')
                    ->label('Valor')
                    ->required()
                    ->numeric()
                    ->prefix('AOA')
                    ->step(0.01),
                
                Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'aluguer' => 'Aluguer',
                        'caucao' => 'Caução',
                        'multa' => 'Multa',
                        'outros' => 'Outros',
                    ])
                    ->default('aluguer')
                    ->required(),
                
                Select::make('method')
                    ->label('Método de Pagamento')
                    ->options([
                        'numerario' => 'Numerário',
                        'transferencia' => 'Transferência',
                        'pos' => 'POS',
                        'outros' => 'Outros',
                    ])
                    ->default('numerario')
                    ->required(),
                
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pago' => 'Pago',
                        'pendente' => 'Pendente',
                        'reembolsado' => 'Reembolsado',
                    ])
                    ->default('pendente')
                    ->required(),
                
                DateTimePicker::make('paid_at')
                    ->label('Data de Pagamento')
                    ->native(false)
                    ->timezone('Africa/Luanda')
                    ->visible(fn ($get) => $get('status') === 'pago'),
            ]);
    }
}
