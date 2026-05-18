<?php

namespace App\Filament\Resources;

use App\Enums\BookingStatus;
use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Booking Management';

    protected static ?string $navigationGroup = 'Booking Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->label('User'),
                Forms\Components\Select::make('booking_type')
                    ->options([
                        'glamping' => 'Glamping',
                        'outbound' => 'Outbound',
                    ])
                    ->required()
                    ->label('Booking Type'),
                Forms\Components\DatePicker::make('booking_date')
                    ->required()
                    ->label('Booking Date'),
                Forms\Components\TextInput::make('token_code')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->label('Token Code'),
                Forms\Components\Select::make('status')
                    ->options(BookingStatus::options())
                    ->required()
                    ->label('Status'),
                Forms\Components\TextInput::make('guest_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Guest Name'),
                Forms\Components\TextInput::make('guest_phone')
                    ->required()
                    ->maxLength(255)
                    ->label('Guest Phone'),
                Forms\Components\TextInput::make('guest_email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->label('Guest Email'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->label('User'),
                Tables\Columns\TextColumn::make('booking_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'glamping' => 'success',
                        'outbound' => 'warning',
                        default => 'gray',
                    })
                    ->label('Type'),
                Tables\Columns\TextColumn::make('booking_date')
                    ->date()
                    ->sortable()
                    ->label('Date'),
                Tables\Columns\TextColumn::make('token_code')
                    ->searchable()
                    ->label('Token'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (BookingStatus $state): string => $state->label())
                    ->color(fn (BookingStatus $state): string => $state->color())
                    ->icon(fn (BookingStatus $state): string => $state->icon())
                    ->label('Status'),
                Tables\Columns\TextColumn::make('guest_name')
                    ->searchable()
                    ->label('Guest'),
                Tables\Columns\TextColumn::make('guest_phone')
                    ->label('Phone'),
                Tables\Columns\TextColumn::make('guest_email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('booking_type')
                    ->options([
                        'glamping' => 'Glamping',
                        'outbound' => 'Outbound',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options(BookingStatus::options()),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->label('Actions')
                ->icon('heroicon-m-ellipsis-horizontal'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BookingDetailsRelationManager::class,
            RelationManagers\BookingOutboundsRelationManager::class,
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}