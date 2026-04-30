<?php

namespace App\Filament\Resources\Goals\Tables;

use App\Models\Goal;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GoalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('goalable_type')
                    ->label('Owner Type')
                    ->formatStateUsing(fn (string $state): string => class_basename($state))
                    ->sortable(),
                TextColumn::make('goalable.name')
                    ->label('Owner')
                    ->sortable(),
                TextColumn::make('target_amount')
                    ->money('KES')
                    ->sortable(),
                TextColumn::make('wallet.balance')
                    ->label('Saved')
                    ->money('KES')
                    ->sortable(),
                TextColumn::make('progress_percent')
                    ->label('Progress')
                    ->state(fn (Goal $record): string => number_format(($record->wallet?->balance / $record->target_amount) * 100, 1) . '%'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
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
