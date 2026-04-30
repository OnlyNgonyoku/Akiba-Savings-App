<?php

namespace App\Filament\Resources\LedgerEntries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LedgerEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')->sortable(),
                TextColumn::make('transaction.reference')
                    ->label('Transaction Ref')
                    ->searchable(),
                TextColumn::make('wallet.walletable.name')
                    ->label('Wallet Owner'),
                TextColumn::make('wallet.type')
                    ->label('Wallet Type'),
                TextColumn::make('entry_type')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'debit' ? 'danger' : 'success'),
                TextColumn::make('amount')
                    ->money('KES')
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(30),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
