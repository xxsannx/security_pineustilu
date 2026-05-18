<?php

namespace App\Filament\Resources\GalleryResource\Pages;

use App\Filament\Resources\GalleryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditGallery extends EditRecord
{
    protected static string $resource = GalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('View'),
            Actions\DeleteAction::make()
                ->label('Delete')
                ->after(function () {
                    // Delete the image file when record is deleted
                    if ($this->record->image_path && Storage::disk('public')->exists($this->record->image_path)) {
                        Storage::disk('public')->delete($this->record->image_path);
                    }
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Gallery updated successfully';
    }
}
