<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'User Management';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'Users';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->description('Basic user information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('country_code')
                                    ->label('Kode Negara')
                                    ->default('+62')
                                    ->maxLength(5),
                                Forms\Components\TextInput::make('phone')
                                    ->label('No. Telepon')
                                    ->tel()
                                    ->maxLength(15),
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Security')
                    ->description('Password settings')
                    ->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->minLength(8)
                            ->same('password_confirmation')
                            ->revealable(),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->dehydrated(false)
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->revealable(),
                    ])->columns(2),

                Forms\Components\Section::make('Role & Permissions')
                    ->description('User role settings')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->label('Role')
                            ->options([
                                'user' => 'User',
                                'admin' => 'Admin',
                                'super-admin' => 'Super Admin',
                            ])
                            ->required()
                            ->default('user')
                            ->native(false)
                            ->afterStateHydrated(function (Forms\Components\Select $component, $record) {
                                if ($record) {
                                    $component->state($record->roles->first()?->name);
                                }
                            }),
                    ]),

                Forms\Components\Section::make('Login Method')
                    ->description('Informasi metode login user')
                    ->schema([
                        Forms\Components\Placeholder::make('login_method_display')
                            ->label('Metode Login')
                            ->content(fn ($record) => $record?->google_id 
                                ? '🌐 Google OAuth' 
                                : '📧 Email/Password'),
                        Forms\Components\TextInput::make('google_id')
                            ->label('Google ID')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn ($record) => $record?->google_id !== null),
                    ])
                    ->columns(2)
                    ->visibleOn(['view', 'edit']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copied successfully'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->formatStateUsing(fn ($record) => $record->phone ? "{$record->country_code} {$record->phone}" : '-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'super-admin' => 'danger',
                        'admin' => 'warning',
                        'user' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('google_id')
                    ->label('Login Via')
                    ->icon(fn ($state) => $state ? 'heroicon-o-globe-alt' : 'heroicon-o-envelope')
                    ->color(fn ($state) => $state ? 'info' : 'gray')
                    ->tooltip(fn ($state) => $state ? 'Login dengan Google' : 'Login dengan Email/Password')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Terverifikasi')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('Belum terverifikasi')
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Role')
                    ->options([
                        'user' => 'User',
                        'admin' => 'Admin',
                        'super-admin' => 'Super Admin',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $role): Builder => $query->whereHas('roles', fn ($q) => $q->where('name', $role))
                        );
                    }),
                Tables\Filters\Filter::make('verified')
                    ->label('Terverifikasi')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
                Tables\Filters\Filter::make('unverified')
                    ->label('Belum Terverifikasi')
                    ->query(fn (Builder $query): Builder => $query->whereNull('email_verified_at')),
                Tables\Filters\SelectFilter::make('login_method')
                    ->label('Metode Login')
                    ->options([
                        'google' => 'Google',
                        'email' => 'Email/Password',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $method): Builder => match ($method) {
                                'google' => $query->whereNotNull('google_id'),
                                'email' => $query->whereNull('google_id'),
                                default => $query,
                            }
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Lihat'),
                    Tables\Actions\EditAction::make()
                        ->label('Edit'),
                    Tables\Actions\Action::make('verifyEmail')
                        ->label('Verifikasi Email')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Verify Email')
                        ->modalDescription('Are you sure you want to verify this user\'s email?')
                        ->action(fn (User $record) => $record->update(['email_verified_at' => now()]))
                        ->visible(fn (User $record) => is_null($record->email_verified_at)),
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus'),
                ])
                ->label('Aksi')
                ->icon('heroicon-m-ellipsis-horizontal'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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
