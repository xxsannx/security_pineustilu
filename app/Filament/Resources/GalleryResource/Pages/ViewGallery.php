<?php

namespace App\Filament\Resources\GalleryResource\Pages;

use App\Filament\Resources\GalleryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewGallery extends ViewRecord
{
    protected static string $resource = GalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit'),
            Actions\DeleteAction::make()
                ->label('Delete'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Image Preview')
                    ->schema([
                        Infolists\Components\ImageEntry::make('image_path')
                            ->label('')
                            ->disk('public')
                            ->width('100%')
                            ->height('auto'),
                    ]),

                Infolists\Components\Section::make('Gallery Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('area.name')
                            ->label('Area')
                            ->placeholder('Dashboard'),
                        Infolists\Components\TextEntry::make('type')
                            ->label('Type')
                            ->badge()
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'header' => 'Area - Header',
                                'skema_deck' => 'Area - Skema Deck',
                                'tent' => 'Area - Tent',
                                'galeri' => 'Area - Galeri',
                                'dashboard_header' => 'Dashboard - Header',
                                'dashboard_map' => 'Dashboard - Peta',
                                'dashboard_galeri' => 'Dashboard - Galeri',
                                default => $state,
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'header' => 'danger',
                                'skema_deck' => 'warning',
                                'tent' => 'success',
                                'galeri' => 'primary',
                                'dashboard_header', 'dashboard_map', 'dashboard_galeri' => 'info',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('description')
                            ->label('Description')
                            ->placeholder('No description')
                            ->columnSpanFull(),
                    ])->columns(2),

                Infolists\Components\Section::make('Time Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d M Y, H:i'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime('d M Y, H:i'),
                    ])->columns(2),
            ]);
    }
}
