<?php

namespace Database\Seeders;

use App\Models\LedgerEntry;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch system user (the very first one)
        $systemUser = User::where('phone', '+254700000000')->firstOrFail();
        $systemWallet = Wallet::where('walletable_type', User::class)
                              ->where('walletable_id', $systemUser->id)
                              ->firstOrFail();

        $users = User::where('id', '!=', $systemUser->id)->get(); // real users only
        $userWallets = Wallet::where('type', 'personal')
                             ->where('walletable_type', User::class)
                             ->whereIn('walletable_id', $users->pluck('id'))
                             ->get();

        $groupWallet = Wallet::where('type', 'group')->first();
        $goalWallet = Wallet::where('type', 'goal_escrow')->first();
        $fundraiserWallet = Wallet::where('type', 'fundraiser')->first();

        // 1. Deposit: user deposits -> credit user, debit system wallet
        $this->createTransaction(
            initiator: $users->first(),
            type: 'deposit',
            amount: 5000.00,
            entries: [
                [$systemWallet, 'debit', 5000.00],   // system pays out
                [$userWallets->first(), 'credit', 5000.00],
            ]
        );

        // 2. Contribution: user -> group
        $this->createTransaction(
            initiator: $users->skip(1)->first(),
            type: 'contribution',
            amount: 1000.00,
            entries: [
                [$userWallets->skip(1)->first(), 'debit', 1000.00],
                [$groupWallet, 'credit', 1000.00],
            ]
        );

        // 3. Transfer to goal
        $this->createTransaction(
            initiator: $users->skip(2)->first(),
            type: 'transfer',
            amount: 2000.00,
            entries: [
                [$userWallets->skip(2)->first(), 'debit', 2000.00],
                [$goalWallet, 'credit', 2000.00],
            ]
        );

        // 4. Harambee contribution
        $this->createTransaction(
            initiator: $users->skip(3)->first(),
            type: 'contribution',
            amount: 500.00,
            entries: [
                [$userWallets->skip(3)->first(), 'debit', 500.00],
                [$fundraiserWallet, 'credit', 500.00],
            ]
        );

        // 5. Payout from group to user
        $this->createTransaction(
            initiator: null,
            type: 'payout',
            amount: 3000.00,
            entries: [
                [$groupWallet, 'debit', 3000.00],
                [$userWallets->first(), 'credit', 3000.00],
            ]
        );
    }

    private function createTransaction($initiator, string $type, float $amount, array $entries): void
    {
        $transaction = Transaction::create([
            'initiator_type' => $initiator ? get_class($initiator) : null,
            'initiator_id' => $initiator?->id,
            'type' => $type,
            'amount' => $amount,
            'reference' => 'TXN-' . strtoupper(Str::random(10)),
            'idempotency_key' => Str::uuid(),
            'status' => 'completed',
            'metadata' => json_encode(['seeded' => true]),
        ]);

        foreach ($entries as [$wallet, $entryType, $entryAmount]) {
            LedgerEntry::create([
                'transaction_id' => $transaction->id,
                'wallet_id' => $wallet->id,
                'entry_type' => $entryType,
                'amount' => $entryAmount,
                'description' => $type . ' transaction',
            ]);

            $balanceChange = $entryType === 'credit' ? $entryAmount : -$entryAmount;
            $wallet->increment('balance', $balanceChange);
        }
    }
}
