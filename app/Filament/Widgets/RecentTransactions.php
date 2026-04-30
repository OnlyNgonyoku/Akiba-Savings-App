<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentTransactions extends BaseWidget
{
    protected static ?int $sort = 5; // Position on dashboard

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::where('status', 'completed')
                    ->latest()
                    ->take(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->label('Ref')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'deposit' => 'success',
                        'contribution' => 'info',
                        'transfer' => 'primary',
                        'payout' => 'warning',
                        'withdrawal' => 'danger',
                        'fee' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->money('KES'),
                Tables\Columns\TextColumn::make('initiator.name')
                    ->label('Initiated by'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime(),
            ])
            ->paginated(false);
    }
}
