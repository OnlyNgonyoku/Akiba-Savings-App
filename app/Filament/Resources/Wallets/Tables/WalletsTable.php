<?php

namespace App\Filament\Resources\Wallets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WalletsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')
                    ->label('Wallet ID')
                    ->sortable(),

                TextColumn::make('walletable_type')
                    ->label('Owner Type')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->sortable(),

                TextColumn::make('walletable_id')
                    ->label('Owner ID')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Wallet Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'personal' => 'success',
                        'group' => 'info',
                        'goal_escrow' => 'warning',
                        'fundraiser' => 'primary',
                        'system_collection' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('balance')
                    ->label('Balance')
                    ->money('KES')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
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
