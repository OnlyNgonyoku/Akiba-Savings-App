<?php

namespace App\Filament\Widgets;

use App\Models\Group;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WithdrawalRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $activeUsers = User::where('phone', '!=', '+254700000000')->count();      // exclude system user

        $totalDeposits = Transaction::where('type', 'deposit')
                            ->where('status', 'completed')
                            ->sum('amount');

        $totalContributions = Transaction::whereIn('type', ['contribution', 'transfer'])
                                ->where('status', 'completed')
                                ->sum('amount');

        $pendingWithdrawals = WithdrawalRequest::where('status', 'pending')->count();

        $activeGroups = Group::count();

        $totalGroupBalance = Wallet::where('type', 'group')->sum('balance');

        $totalGoalProgress = Wallet::where('type', 'goal_escrow')->sum('balance');

        $recentTransactions = Transaction::where('status', 'completed')
                                ->where('created_at', '>=', now()->subDays(7))
                                ->count();

        return [
            Stat::make('Active Users', $activeUsers)
                ->description('Registered members')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Total Deposits', number_format($totalDeposits, 2) . ' KES')
                ->description('All completed deposits')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Contributions (All Time)', number_format($totalContributions, 2) . ' KES')
                ->description('Group & goal transfers')
                ->icon('heroicon-o-arrows-up-down')
                ->color('info'),

            Stat::make('Pending Withdrawals', $pendingWithdrawals)
                ->description('Requires attention')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Active Groups', $activeGroups)
                ->description('Chamas running')
                ->icon('heroicon-o-user-group')
                ->color('primary'),

            Stat::make('Group Balances', number_format($totalGroupBalance, 2) . ' KES')
                ->description('Total in group wallets')
                ->icon('heroicon-o-building-library')
                ->color('success'),

            Stat::make('Goal Progress', number_format($totalGoalProgress, 2) . ' KES')
                ->description('Saved toward goals')
                ->icon('heroicon-o-briefcase')
                ->color('info'),

            Stat::make('Weekly Transactions', $recentTransactions)
                ->description('Last 7 days')
                ->icon('heroicon-o-arrow-path')
                ->color('gray'),
        ];
    }
}
