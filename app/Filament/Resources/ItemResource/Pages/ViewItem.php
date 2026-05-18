<?php

namespace App\Filament\Resources\ItemResource\Pages;

use App\Filament\Resources\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewItem extends ViewRecord
{
    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit'),
            Actions\DeleteAction::make()
                ->label('Delete')
                ->requiresConfirmation()
                ->modalHeading('Delete Item')
                ->modalDescription('Are you sure you want to delete this item?')
                ->modalSubmitActionLabel('Yes, Delete'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Item Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Item Name')
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('type')
                            ->label('Item Type')
                            ->badge()
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'perlengkapan' => 'Perlengkapan',
                                'makanan' => 'Makanan & Minuman',
                                'sewa' => 'Sewa Alat',
                                'layanan' => 'Additional Services',
                                'lainnya' => 'Lainnya',
                                default => $state ?? '-',
                            })
                            ->color(fn ($state) => match ($state) {
                                'perlengkapan' => 'primary',
                                'makanan' => 'success',
                                'sewa' => 'warning',
                                'layanan' => 'info',
                                'lainnya' => 'gray',
                                default => 'gray',
                            }),

                        Infolists\Components\TextEntry::make('price')
                            ->label('Price')
                            ->money('IDR')
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('description')
                            ->label('Description')
                            ->placeholder('Tidak ada deskripsi')
                            ->columnSpanFull(),
                    ])->columns(2),

                Infolists\Components\Section::make('Time Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d M Y, H:i'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('d M Y, H:i'),
                    ])->columns(2)
                    ->collapsible(),
            ]);
    }
}
