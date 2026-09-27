<?php

namespace App\Filament\Resources\Clients\Pages;

use App\Filament\Resources\Clients\ClientResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['role'] = 'cliente';

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->syncSpatieRoleFromEnum();
    }
}
