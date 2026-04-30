<?php

namespace App\Filament\Resources\Fundraisers\Tables;

use App\Models\Fundraiser;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FundraisersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('creator.name')
                    ->label('Creator')
                    ->sortable(),
                TextColumn::make('target_amount')
                    ->money('KES')
                    ->sortable(),
                TextColumn::make('wallet.balance')
                    ->label('Raised')
                    ->money('KES')
                    ->sortable(),
                TextColumn::make('progress')
                    ->state(fn (Fundraiser $record): string => number_format(($record->wallet?->balance / $record->target_amount) * 100, 1) . '%'),
                TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime(),
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
