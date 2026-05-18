<?php

namespace App\Filament\Resources\OutboundResource\Pages;

use App\Filament\Resources\OutboundResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOutbound extends ViewRecord
{
    protected static string $resource = OutboundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit'),
            Actions\DeleteAction::make()
                ->label('Hapus'),
        ];
    }
}
