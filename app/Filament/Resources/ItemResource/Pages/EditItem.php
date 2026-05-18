<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditItem extends EditRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('View'),
            Actions\DeleteAction::make()
                ->label('Delete')
                ->requiresConfirmation()
                ->modalHeading('Delete Item')
                ->modalDescription('Are you sure you want to delete this item? Related price data will also be deleted.')
                ->modalSubmitActionLabel('Yes, Delete'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Item updated successfully')
            ->body('Changes to additional item have been saved.');
    }
}
