<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    protected static ?string $title = 'Payments';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->label('Order ID'),
                Forms\Components\TextInput::make('transaction_id')
                    ->label('Transaction ID'),
                Forms\Components\TextInput::make('payment_type')
                    ->label('Payment Type'),
                Forms\Components\Select::make('transaction_status')
                    ->options([
                        'pending' => 'Pending',
                        'settlement' => 'Settlement',
                        'capture' => 'Capture',
                        'deny' => 'Deny',
                        'cancel' => 'Cancel',
                        'expire' => 'Expire',
                        'failure' => 'Failure',
                    ])
                    ->required()
                    ->label('Transaction Status'),
                Forms\Components\TextInput::make('gross_amount')
                    ->numeric()
                    ->prefix('Rp')
                    ->required()
                    ->label('Amount'),
                Forms\Components\TextInput::make('bank')
                    ->label('Bank'),
                Forms\Components\TextInput::make('va_number')
                    ->label('VA Number'),
                Forms\Components\DateTimePicker::make('expired_at')
                    ->label('Expires At'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->searchable()
                    ->label('Order ID'),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->searchable()
                    ->label('Transaction ID')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('payment_type')
                    ->badge()
                    ->label('Type'),
                Tables\Columns\TextColumn::make('transaction_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'settlement', 'capture' => 'success',
                        'pending' => 'warning',
                        'deny', 'cancel', 'expire', 'failure' => 'danger',
                        default => 'gray',
                    })
                    ->label('Status'),
                Tables\Columns\TextColumn::make('gross_amount')
                    ->money('IDR')
                    ->label('Amount'),
                Tables\Columns\TextColumn::make('bank')
                    ->label('Bank')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('expired_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Expires'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('transaction_status')
                    ->options([
                        'pending' => 'Pending',
                        'settlement' => 'Settlement',
                        'capture' => 'Capture',
                        'deny' => 'Deny',
                        'cancel' => 'Cancel',
                        'expire' => 'Expire',
                        'failure' => 'Failure',
                    ]),
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
