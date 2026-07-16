<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        
        $changes = [];
        $original = $record->getOriginal();
        
        if ($record->wasChanged('status')) {
            $oldStatus = $original['status'] instanceof \App\Enums\BookingStatus ? $original['status']->value : $original['status'];
            $newStatus = $record->status instanceof \App\Enums\BookingStatus ? $record->status->value : $record->status;
            $changes[] = "status dari '{$oldStatus}' menjadi '{$newStatus}'";
        }
        
        if (!empty($changes)) {
            \App\Services\AuditLogService::log(
                'admin_booking_updated',
                "Admin memperbarui booking ID {$record->id} (Token: {$record->token_code}): " . implode(', ', $changes),
                auth()->id(),
                'WARNING'
            );
        }
    }
}