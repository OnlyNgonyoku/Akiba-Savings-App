<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;

class GroupContributionActivityChart extends ChartWidget
{
    protected ?string $heading = 'Group Contributions (Last 30 Days)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $contributions = Transaction::where('type', 'contribution')
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->with('ledgerEntries.wallet.walletable')
            ->get();

        // Group by group wallet owner name (polymorphic)
        $grouped = $contributions->flatMap->ledgerEntries
            ->filter(fn ($entry) => $entry->wallet->type === 'group')
            ->groupBy(fn ($entry) => $entry->wallet->walletable->name ?? 'Unknown Group')
            ->map(fn ($entries) => $entries->sum('amount'));

        return [
            'datasets' => [
                [
                    'label' => 'Contribution Amount (KES)',
                    'data' => $grouped->values()->toArray(),
                    'backgroundColor' => '#6366f1',
                ],
            ],
            'labels' => $grouped->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
