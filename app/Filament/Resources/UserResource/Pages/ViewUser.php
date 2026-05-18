<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit'),
            Actions\DeleteAction::make()
                ->label('Hapus'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('User Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Nama Lengkap'),
                        Infolists\Components\TextEntry::make('email')
                            ->label('Email')
                            ->copyable()
                            ->copyMessage('Email copied successfully'),
                        Infolists\Components\TextEntry::make('phone')
                            ->label('No. Telepon')
                            ->formatStateUsing(fn ($record) => $record->phone ? "{$record->country_code} {$record->phone}" : '-'),
                        Infolists\Components\TextEntry::make('roles.name')
                            ->label('Role')
                            ->badge()
                            ->formatStateUsing(fn ($state) => ucfirst($state))
                            ->color(fn (string $state): string => match ($state) {
                                'super-admin' => 'danger',
                                'admin' => 'warning',
                                'user' => 'success',
                                default => 'gray',
                            }),
                    ])->columns(2),

                Infolists\Components\Section::make('Status & Time')
                    ->schema([
                        Infolists\Components\TextEntry::make('email_verified_at')
                            ->label('Email Terverifikasi')
                            ->dateTime('d M Y, H:i')
                            ->placeholder('Belum terverifikasi'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d M Y, H:i'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime('d M Y, H:i'),
                    ])->columns(3),
            ]);
    }
}
