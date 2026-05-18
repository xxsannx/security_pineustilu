<?php

namespace App\Filament\Resources\OutboundResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $recordTitleAttribute = 'variant_label';

    protected static ?string $title = 'Variant Outbound';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('variant_type')
                    ->label('Tipe Variant')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('variant_label')
                    ->label('Label Variant')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('min_pax_per_unit')
                    ->label('Min Pax per Unit')
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('max_pax_per_unit')
                    ->label('Max Pax per Unit')
                    ->numeric(),
                Forms\Components\Toggle::make('includes_documentation')
                    ->label('Termasuk Dokumentasi')
                    ->default(false),
                Forms\Components\TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('variant_label')
            ->columns([
                Tables\Columns\TextColumn::make('variant_type')
                    ->label('Tipe')
                    ->searchable(),
                Tables\Columns\TextColumn::make('variant_label')
                    ->label('Label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('min_pax_per_unit')
                    ->label('Min Pax'),
                Tables\Columns\TextColumn::make('max_pax_per_unit')
                    ->label('Max Pax'),
                Tables\Columns\TextColumn::make('prices.price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : '-')
                    ->sortable(),
                Tables\Columns\IconColumn::make('includes_documentation')
                    ->label('Dokumentasi')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Variant'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
