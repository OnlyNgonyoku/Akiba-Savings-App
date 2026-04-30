<?php

namespace App\Filament\Resources\WithdrawalRequests\Tables;

use App\Models\WithdrawalRequest;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class WithdrawalRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(WithdrawalRequest::query())
            ->columns([
                //
                TextColumn::make('id')->sortable(),
                TextColumn::make('user.name')
                    ->label('Requested by'),
                TextColumn::make('wallet.walletable.name')
                    ->label('From Wallet'),
                TextColumn::make('amount')
                    ->money('KES')
                    ->sortable(),
                TextColumn::make('destination')
                    ->label('Destination'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
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
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    // FIX: allow $record to be nullable
                    ->visible(function (?WithdrawalRequest $record): bool {
                        return $record !== null && $record->status === 'pending';
                    })
                    ->action(function (WithdrawalRequest $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => Auth::id(),
                        ]);
                        // Trigger payout job here later
                    })
                    ->requiresConfirmation(),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    // FIX: allow $record to be nullable
                    ->visible(function (?WithdrawalRequest $record): bool {
                        return $record !== null && $record->status === 'pending';
                    })
                    ->action(function (WithdrawalRequest $record) {
                        $record->update([
                            'status' => 'rejected',
                            'approved_by' => Auth::id(),
                        ]);
                    })
                    ->requiresConfirmation(),
                ]);
    }
}
