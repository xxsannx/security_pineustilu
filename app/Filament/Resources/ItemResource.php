<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Additional Items Management';

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $modelLabel = 'Additional Item';

    protected static ?string $pluralModelLabel = 'Additional Items';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Item Information')
                    ->description('Main additional item data')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Item Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Example: Extra Bed, Sleeping Bag, etc.')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('type')
                            ->label('Unit Type')
                            ->options([
                                'pax' => 'Per Person (pax)',
                                'set' => 'Per Set',
                                'pack' => 'Per Pack',
                                'bottle' => 'Per Bottle',
                                'bundle' => 'Per Bundle',
                                'bag' => 'Per Bag',
                                'piece' => 'Per Piece',
                                'unit' => 'Per Unit',
                            ])
                            ->required()
                            ->native(false)
                            ->placeholder('Select unit type'),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->maxLength(1000)
                            ->placeholder('Brief description about this item')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Price')
                    ->description('Set item price')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('0')
                            ->minValue(0)
                            ->helperText('Enter price in Rupiah'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Item Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pax' => 'pax',
                        'set' => 'set',
                        'pack' => 'pack',
                        'bottle' => 'bottle',
                        'bundle' => 'bundle',
                        'bag' => 'bag',
                        'piece' => 'piece',
                        'unit' => 'unit',
                        default => $state ?? '-',
                    })
                    ->color(fn ($state) => match ($state) {
                        'pax' => 'primary',
                        'set' => 'success',
                        'pack' => 'warning',
                        'bottle' => 'info',
                        'bundle' => 'danger',
                        'bag' => 'gray',
                        'piece' => 'primary',
                        'unit' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->placeholder('No description')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Unit Type')
                    ->options([
                        'pax' => 'Per Person (pax)',
                        'set' => 'Per Set',
                        'pack' => 'Per Pack',
                        'bottle' => 'Per Bottle',
                        'bundle' => 'Per Bundle',
                        'bag' => 'Per Bag',
                        'piece' => 'Per Piece',
                        'unit' => 'Per Unit',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('View'),
                    Tables\Actions\EditAction::make()
                        ->label('Edit'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Delete')
                        ->requiresConfirmation()
                        ->modalHeading('Delete Item')
                        ->modalDescription('Are you sure you want to delete this item? Related price data will also be deleted.')
                        ->modalSubmitActionLabel('Yes, Delete'),
                ])
                ->label('Actions')
                ->icon('heroicon-m-ellipsis-horizontal'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Delete Selected')
                        ->requiresConfirmation()
                        ->modalHeading('Delete Selected Items')
                        ->modalDescription('Are you sure you want to delete the selected items? Related price data will also be deleted.')
                        ->modalSubmitActionLabel('Yes, Delete All'),
                ]),
            ])
            ->emptyStateHeading('No additional items yet')
            ->emptyStateDescription('Add additional items such as equipment, food, or other services.')
            ->emptyStateIcon('heroicon-o-cube')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label('Add Item')
                    ->url(route('filament.admin.resources.items.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'view' => Pages\ViewItem::route('/{record}'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
}
