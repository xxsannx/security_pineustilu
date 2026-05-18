<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class BookingOutboundsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookingOutbounds';

    protected static ?string $title = 'Outbound Details';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('outbound_id')
                    ->relationship('outbound', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Outbound Activity'),
                Forms\Components\Select::make('outbound_variant_id')
                    ->relationship('outboundVariant', 'variant_label')
                    ->searchable()
                    ->preload()
                    ->label('Variant'),
                Forms\Components\DatePicker::make('schedule_date')
                    ->required()
                    ->label('Activity Date'),
                Forms\Components\TimePicker::make('schedule_time')
                    ->label('Activity Time'),
                Forms\Components\TextInput::make('total_participants')
                    ->numeric()
                    ->required()
                    ->label('Total Participants'),
                Forms\Components\Toggle::make('add_documentation')
                    ->label('Documentation'),
                Forms\Components\TextInput::make('documentation_fee')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Documentation Fee'),
                Forms\Components\Toggle::make('need_transportation')
                    ->label('Transportation'),
                Forms\Components\TextInput::make('transportation_fee')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Transportation Fee'),
                Forms\Components\TextInput::make('base_price')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Base Price'),
                Forms\Components\TextInput::make('subtotal')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Subtotal'),
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
                Tables\Columns\TextColumn::make('outbound.name')
                    ->label('Activity')
                    ->searchable(),
                Tables\Columns\TextColumn::make('outboundVariant.variant_label')
                    ->label('Variant')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('schedule_date')
                    ->date()
                    ->sortable()
                    ->label('Date'),
                Tables\Columns\TextColumn::make('total_participants')
                    ->numeric()
                    ->label('Participants'),
                Tables\Columns\IconColumn::make('add_documentation')
                    ->boolean()
                    ->label('Docs'),
                Tables\Columns\IconColumn::make('need_transportation')
                    ->boolean()
                    ->label('Transport'),
                Tables\Columns\TextColumn::make('subtotal')
                    ->money('IDR')
                    ->label('Subtotal'),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('IDR')
                    ->label('Total'),
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
