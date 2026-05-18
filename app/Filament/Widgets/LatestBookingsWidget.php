<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestBookingsWidget extends BaseWidget
{
    protected static ?string $heading = 'Latest Bookings';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->with(['user', 'bookingDetails.unit.area'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('token_code')
                    ->label('Booking Code')
                    ->searchable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Booking code copied'),

                Tables\Columns\TextColumn::make('guest_name')
                    ->label('Guest Name')
                    ->searchable()
                    ->placeholder('Via User')
                    ->default(fn ($record) => $record->user?->name ?? '-'),

                Tables\Columns\TextColumn::make('booking_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'area' => 'Area Camping',
                        'outbound' => 'Outbound',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'area' => 'success',
                        'outbound' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('booking_date')
                    ->label('Booking Date')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'completed' => 'primary',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Booking $record): string => route('filament.admin.resources.bookings.view', $record)),
            ])
            ->paginated(false);
    }
}
