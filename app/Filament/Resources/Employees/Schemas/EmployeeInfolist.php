<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações do Funcionário')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Nome'),
                        
                        TextEntry::make('email')
                            ->label('Email'),
                        
                        TextEntry::make('phone')
                            ->label('Telefone')
                            ->placeholder('N/A'),
                        
                        TextEntry::make('address')
                            ->label('Endereço')
                            ->placeholder('N/A'),
                        
                        TextEntry::make('role')
                            ->label('Função')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'gerente' => 'danger',
                                'caixa' => 'info',
                                'motorista' => 'warning',
                                'tecnico' => 'success',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'gerente' => 'Gerente',
                                'caixa' => 'Caixa',
                                'motorista' => 'Motorista',
                                'tecnico' => 'Técnico',
                                default => $state,
                            }),
                        
                        TextEntry::make('created_at')
                            ->label('Data de Criação')
                            ->dateTime('d/m/Y H:i'),
                        
                        TextEntry::make('updated_at')
                            ->label('Última Atualização')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2),
            ]);
    }
}

