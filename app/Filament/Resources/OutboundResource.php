<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutboundResource\Pages;
use App\Filament\Resources\OutboundResource\RelationManagers;
use App\Models\Outbound;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OutboundResource extends Resource
{
    protected static ?string $model = Outbound::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Manajemen Outbound';

    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535),
                        Forms\Components\TextInput::make('unit_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric(),
                    ])->columns(2),

                Forms\Components\Section::make('Capacity & Requirements')
                    ->schema([
                        Forms\Components\TextInput::make('min_participants')
                            ->numeric()
                            ->minValue(1),
                        Forms\Components\TextInput::make('max_participants')
                            ->numeric()
                            ->minValue(1),
                        Forms\Components\TextInput::make('min_age')
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make('duration')
                            ->numeric()
                            ->suffix('hours'),
                        Forms\Components\TextInput::make('distance')
                            ->numeric()
                            ->suffix('km'),
                    ])->columns(3),

                Forms\Components\Section::make('Features & Options')
                    ->schema([
                        Forms\Components\Select::make('pricing_type')
                            ->options([
                                'per_pax' => 'Per Person (Per Pax)',
                                'per_unit' => 'Per Unit (Boat/Car/etc)',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('has_variants'),
                        Forms\Components\Toggle::make('allows_documentation_addon'),
                        Forms\Components\Toggle::make('requires_transportation'),
                        Forms\Components\Toggle::make('has_camping_package'),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pricing_type')
                    ->badge(),
                Tables\Columns\TextColumn::make('min_participants')
                    ->label('Min Pax'),
                Tables\Columns\TextColumn::make('max_participants')
                    ->label('Max Pax'),
                Tables\Columns\TextColumn::make('duration')
                    ->suffix(' hours'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('pricing_type')
                    ->options([
                        'per_pax' => 'Per Person (Per Pax)',
                        'per_unit' => 'Per Unit (Boat/Car/etc)',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->label('Aksi')
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
            RelationManagers\VariantsRelationManager::class,
            RelationManagers\PricesRelationManager::class,
            RelationManagers\FacilitiesRelationManager::class,
            RelationManagers\GalleriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOutbounds::route('/'),
            'create' => Pages\CreateOutbound::route('/create'),
            'view' => Pages\ViewOutbound::route('/{record}'),
            'edit' => Pages\EditOutbound::route('/{record}/edit'),
        ];
    }
}
