<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DepositsVsWithdrawalsChart extends ChartWidget
{
    protected ?string $heading = 'Deposits vs Payouts (Last 30 Days';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $deposits = Transaction::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->where('type', 'deposit')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $payouts = Transaction::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->whereIn('type', ['payout', 'withdrawal'])
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // Build a complete date range
        $dates = [];
        for ($i = 30; $i >= 0; $i--) {
            $dates[] = now()->subDays($i)->format('Y-m-d');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Deposits (KES)',
                    'data' => collect($dates)->map(fn ($date) => $deposits->get($date, 0))->toArray(),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16,185,129,0.1)',
                ],
                [
                    'label' => 'Payouts / Withdrawals (KES)',
                    'data' => collect($dates)->map(fn ($date) => $payouts->get($date, 0))->toArray(),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239,68,68,0.1)',
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
