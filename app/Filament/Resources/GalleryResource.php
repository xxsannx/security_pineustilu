<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use App\Models\Area;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Gallery Management';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $modelLabel = 'Gallery';

    protected static ?string $pluralModelLabel = 'Galleries';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Upload Image')
                    ->description('Upload image for gallery')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Image')
                            ->image()
                            ->required()
                            ->directory('galleries')
                            ->disk('public')
                            ->imageResizeMode('cover')
                            ->maxSize(5120) // 5MB
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Format: JPG, PNG, WebP. Maximum 5MB.'),
                    ]),

                Forms\Components\Section::make('Image Details')
                    ->description('Gallery detail information')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->options([
                                // Area
                                'header' => 'Area - Header',
                                'skema_deck' => 'Area - Skema Deck',
                                'tent' => 'Area - Tent',
                                'galeri' => 'Area - Galeri',
                                // Dashboard
                                'dashboard_header' => 'Dashboard - Header',
                                'dashboard_map' => 'Dashboard - Map',
                                'dashboard_galeri' => 'Dashboard - Gallery',
                            ])
                            ->default('galeri')
                            ->required()
                            ->native(false)
                            ->live()
                            // @phpstan-ignore-next-line
                            ->afterStateUpdated(function ($set, $state): void {
                                if (str_starts_with($state ?? '', 'dashboard_')) {
                                    $set('area_id', null);
                                }
                            }),
                        Forms\Components\Select::make('area_id')
                            ->label('Area')
                            ->relationship('area', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select area')
                            // @phpstan-ignore-next-line
                            ->required(function ($get): bool {
                                return !str_starts_with($get('type') ?? '', 'dashboard_');
                            })
                            // @phpstan-ignore-next-line
                            ->visible(function ($get): bool {
                                return !str_starts_with($get('type') ?? '', 'dashboard_');
                            })
                            ->helperText('Select area for this gallery'),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Brief description about the image')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ViewColumn::make('image_path')
                    ->label('Image')
                    ->view('filament.tables.columns.gallery-image'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(40)
                    ->searchable()
                    ->placeholder('No description'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'header' => 'Area - Header',
                        'skema_deck' => 'Area - Skema Deck',
                        'tent' => 'Area - Tent',
                        'galeri' => 'Area - Galeri',
                        'dashboard_header' => 'Dashboard - Header',
                        'dashboard_map' => 'Dashboard - Map',
                        'dashboard_galeri' => 'Dashboard - Gallery',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'header' => 'danger',
                        'skema_deck' => 'warning',
                        'tent' => 'success',
                        'galeri' => 'primary',
                        'dashboard_header' => 'info',
                        'dashboard_map' => 'info',
                        'dashboard_galeri' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('area.name')
                    ->label('Area')
                    ->placeholder('Dashboard')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'header' => 'Area - Header',
                        'skema_deck' => 'Area - Skema Deck',
                        'tent' => 'Area - Tent',
                        'galeri' => 'Area - Galeri',
                        'dashboard_header' => 'Dashboard - Header',
                        'dashboard_map' => 'Dashboard - Map',
                        'dashboard_galeri' => 'Dashboard - Gallery',
                    ]),
                Tables\Filters\SelectFilter::make('area_id')
                    ->label('Area')
                    ->relationship('area', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('dashboard_only')
                    ->label('Dashboard Only')
                    ->query(fn (Builder $query): Builder => $query->where('type', 'like', 'dashboard_%')),
                Tables\Filters\Filter::make('area_only')
                    ->label('Area Only')
                    ->query(fn (Builder $query): Builder => $query->where('type', 'not like', 'dashboard_%')),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('View'),
                    Tables\Actions\EditAction::make()
                        ->label('Edit'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Delete')
                        ->after(function (Gallery $record): void {
                            // Delete the image file when record is deleted
                            /** @var string|null $imagePath */
                            $imagePath = $record->getAttribute('image_path');
                            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                                Storage::disk('public')->delete($imagePath);
                            }
                        }),
                ])
                ->label('Actions')
                ->icon('heroicon-m-ellipsis-horizontal'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Delete')
                        ->after(function ($records): void {
                            // Delete image files when bulk delete
                            foreach ($records as $record) {
                                /** @var string|null $imagePath */
                                $imagePath = $record->getAttribute('image_path');
                                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                                    Storage::disk('public')->delete($imagePath);
                                }
                            }
                        }),
                ]),
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
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'view' => Pages\ViewGallery::route('/{record}'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
