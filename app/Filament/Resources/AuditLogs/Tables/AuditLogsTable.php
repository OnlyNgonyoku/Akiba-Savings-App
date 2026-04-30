<?php

namespace App\Filament\Resources\AuditLogs\Tables;

use App\Models\AuditLog;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(AuditLog::latest())
            ->columns([
                TextColumn::make('user.name')
                    ->label('Actor'),
                TextColumn::make('event')
                    ->badge(),
                TextColumn::make('auditable_type')
                    ->label('Audited Model')
                    ->formatStateUsing(fn (string $state): string => class_basename($state)),
                TextColumn::make('auditable_id'),
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
