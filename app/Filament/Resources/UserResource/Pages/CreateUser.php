<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'User added successfully';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['role']);
        return $data;
    }

    protected function afterCreate(): void
    {
        $role = $this->data['role'] ?? 'user';
        $this->record->syncRoles([$role]);
    }
}
