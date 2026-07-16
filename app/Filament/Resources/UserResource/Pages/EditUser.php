<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('Lihat'),
            Actions\DeleteAction::make()
                ->label('Hapus'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'User updated successfully';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['role']);
        return $data;
    }

    protected function afterSave(): void
    {
        $oldRoles = $this->record->getRoleNames()->toArray();
        $role = $this->data['role'] ?? 'user';
        
        $this->record->syncRoles([$role]);
        
        $newRoles = $this->record->fresh()->getRoleNames()->toArray();
        
        if ($oldRoles !== $newRoles) {
            \App\Services\AuditLogService::log(
                'role_escalation',
                "Admin mengubah role user {$this->record->email} dari [" . implode(', ', $oldRoles) . "] menjadi [" . implode(', ', $newRoles) . "]",
                auth()->id(),
                'CRITICAL'
            );
        }
    }
}
