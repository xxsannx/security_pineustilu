<?php

namespace App\Filament\Resources\AreaResource\Pages;

use App\Filament\Resources\AreaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArea extends EditRecord
{
    protected static string $resource = AreaResource::class;

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
        
        if ($record->wasChanged('name')) {
            $changes[] = "nama dari '{$original['name']}' menjadi '{$record->name}'";
        }
        if ($record->wasChanged('base_price')) {
            $changes[] = "harga dasar dari '{$original['base_price']}' menjadi '{$record->base_price}'";
        }
        
        if (!empty($changes)) {
            \App\Services\AuditLogService::log(
                'admin_area_updated',
                "Admin memperbarui area ID {$record->id} ({$record->name}): " . implode(', ', $changes),
                auth()->id(),
                'WARNING'
            );
        }
    }
}
