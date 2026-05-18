<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class BookingDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookingDetails';

    protected static ?string $title = 'Glamping Details';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('unit_id')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Unit'),
                Forms\Components\DatePicker::make('check_in')
                    ->required()
                    ->label('Check In'),
                Forms\Components\DatePicker::make('check_out')
                    ->required()
                    ->label('Check Out'),
                Forms\Components\TextInput::make('number_of_people')
                    ->numeric()
                    ->required()
                    ->label('Number of People'),
                Forms\Components\TextInput::make('total_extra_charge')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Extra Charge'),
                Forms\Components\TextInput::make('total_price')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->label('Total Price'),
                Forms\Components\Textarea::make('note')
                    ->label('Notes')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('unit.name')
                    ->label('Unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('unit.area.name')
                    ->label('Area')
                    ->searchable(),
                Tables\Columns\TextColumn::make('check_in')
                    ->date()
                    ->sortable()
                    ->label('Check In'),
                Tables\Columns\TextColumn::make('check_out')
                    ->date()
                    ->sortable()
                    ->label('Check Out'),
                Tables\Columns\TextColumn::make('number_of_people')
                    ->numeric()
                    ->label('Guests'),
                Tables\Columns\TextColumn::make('total_extra_charge')
                    ->money('IDR')
                    ->label('Extra Charge'),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('IDR')
                    ->label('Total Price'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
