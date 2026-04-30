<?php

namespace App\Filament\Widgets;

use App\Models\WithdrawalRequest as ModelsWithdrawalRequest;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use WithdrawalRequest;

class PendingWithdrawals extends TableWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ModelsWithdrawalRequest::where('status', 'pending')
                    ->with(['user', 'wallet.walletable']))
            ->columns([
                //
                TextColumn::make('user.name')
                    ->label('Requested by')
                    ->searchable(),
                TextColumn::make('amount')
                    ->money('KES')
                    ->sortable(),
                TextColumn::make('destination')
                    ->label('Destination')
                    ->searchable(),
                TextColumn::make('wallet.walletable.name')
                    ->label('From Wallet')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Requested')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (\App\Models\WithdrawalRequest $record) {
                        $record->update(['status' => 'approved', 'approved_by' => Auth::id()]);
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (\App\Models\WithdrawalRequest $record) {
                        $record->update(['status' => 'rejected', 'approved_by' => Auth::id()]);
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
