<?php

namespace Database\Seeders;

use App\Models\LedgerEntry;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $systemUser = User::where('phone', '+254700000000')->firstOrFail();

        $systemWallet = Wallet::where('walletable_type', User::class)
            ->where('walletable_id', $systemUser->id)
            ->firstOrFail();

        $users = User::where('id', '!=', $systemUser->id)->get();

        $userWallets = Wallet::where('type', 'personal')
            ->where('walletable_type', User::class)
            ->whereIn('walletable_id', $users->pluck('id'))
            ->get();

        $groupWallet = Wallet::where('type', 'group')->first();
        $goalWallet = Wallet::where('type', 'goal_escrow')->first();
        $fundraiserWallet = Wallet::where('type', 'fundraiser')->first();

        // 🧠 Base time (start 30 days ago)
        $baseTime = Carbon::now()->subDays(15);

        $this->createTransaction(
            initiator: $users->first(),
            type: 'deposit',
            amount: 25000.00,
            entries: [
                [$systemWallet, 'debit', 5000.00],
                [$userWallets->first(), 'credit', 5000.00],
            ],
            time: $baseTime->copy()->addDays(2)->addHours(rand(1, 12))
        );

        $this->createTransaction(
            initiator: $users->skip(1)->first(),
            type: 'contribution',
            amount: 1000.00,
            entries: [
                [$userWallets->skip(1)->first(), 'debit', 1000.00],
                [$groupWallet, 'credit', 1000.00],
            ],
            time: $baseTime->copy()->addDays(5)->addHours(rand(1, 12))
        );

        $this->createTransaction(
            initiator: $users->skip(2)->first(),
            type: 'transfer',
            amount: 2000.00,
            entries: [
                [$userWallets->skip(2)->first(), 'debit', 2000.00],
                [$goalWallet, 'credit', 2000.00],
            ],
            time: $baseTime->copy()->addDays(11)->addHours(rand(1, 12))
        );

        $this->createTransaction(
            initiator: $users->skip(3)->first(),
            type: 'contribution',
            amount: 500.00,
            entries: [
                [$userWallets->skip(3)->first(), 'debit', 500.00],
                [$fundraiserWallet, 'credit', 500.00],
            ],
            time: $baseTime->copy()->addDays(3)->addHours(rand(1, 12))
        );

        $this->createTransaction(
            initiator: null,
            type: 'payout',
            amount: 32000.00,
            entries: [
                [$groupWallet, 'debit', 32000.00],
                [$userWallets->first(), 'credit', 32000.00],
            ],
            time: $baseTime->copy()->addDays(9)->addHours(rand(1, 12))
        );

                $this->createTransaction(
            initiator: $users->skip(3)->first(),
            type: 'contribution',
            amount: 500.00,
            entries: [
                [$userWallets->skip(3)->first(), 'debit', 500.00],
                [$fundraiserWallet, 'credit', 500.00],
            ],
            time: $baseTime->copy()->addDays(18)->addHours(rand(1, 12))
        );

        $this->createTransaction(
            initiator: null,
            type: 'payout',
            amount: 48000.00,
            entries: [
                [$groupWallet, 'debit', 48000.00],
                [$userWallets->first(), 'credit', 48000.00],
            ],
            time: $baseTime->copy()->addDays(5)->addHours(rand(1, 12))
        );

        $this->createTransaction(
            initiator: $users->first(),
            type: 'deposit',
            amount: 69000.00,
            entries: [
                [$systemWallet, 'debit', 5000.00],
                [$userWallets->first(), 'credit', 5000.00],
            ],
            time: $baseTime->copy()->addDays(4)->addHours(rand(1, 12))
        );

        $this->createTransaction(
            initiator: $users->skip(1)->first(),
            type: 'contribution',
            amount: 1000.00,
            entries: [
                [$userWallets->skip(1)->first(), 'debit', 1000.00],
                [$groupWallet, 'credit', 1000.00],
            ],
            time: $baseTime->copy()->addDays(3)->addHours(rand(1, 12))
        );

        $this->createTransaction(
            initiator: $users->skip(2)->first(),
            type: 'transfer',
            amount: 2000.00,
            entries: [
                [$userWallets->skip(2)->first(), 'debit', 2000.00],
                [$goalWallet, 'credit', 2000.00],
            ],
            time: $baseTime->copy()->addDays(7)->addHours(rand(1, 12))
        );

        $this->createTransaction(
            initiator: $users->first(),
            type: 'deposit',
            amount: 56000.00,
            entries: [
                [$systemWallet, 'debit', 56000.00],
                [$userWallets->first(), 'credit', 56000.00],
            ],
            time: $baseTime->copy()->addDays(8)->addHours(rand(1, 12))
        );

        $this->createTransaction(
            initiator: $users->skip(1)->first(),
            type: 'payout',
            amount: 78000.00,
            entries: [
                [$userWallets->skip(1)->first(), 'debit', 78000.00],
                [$groupWallet, 'credit', 78000.00],
            ],
            time: $baseTime->copy()->addDays(6)->addHours(rand(1, 12))
        );

        $this->createTransaction(
            initiator: $users->skip(2)->first(),
            type: 'transfer',
            amount: 2000.00,
            entries: [
                [$userWallets->skip(2)->first(), 'debit', 2000.00],
                [$goalWallet, 'credit', 2000.00],
            ],
            time: $baseTime->copy()->addDays(10)->addHours(rand(1, 12))
        );
    }

    private function createTransaction(
        $initiator,
        string $type,
        float $amount,
        array $entries,
        Carbon $time
    ): void
    {
        $transaction = Transaction::create([
            'initiator_type' => $initiator ? get_class($initiator) : null,
            'initiator_id' => $initiator?->id,
            'type' => $type,
            'amount' => $amount,
            'reference' => 'TXN-' . strtoupper(Str::random(10)),
            'idempotency_key' => Str::uuid(),
            'status' => 'completed',
            'metadata' => ['seeded' => true],
            'created_at' => $time,
            'updated_at' => $time,
        ]);

        foreach ($entries as [$wallet, $entryType, $entryAmount]) {
            LedgerEntry::create([
                'transaction_id' => $transaction->id,
                'wallet_id' => $wallet->id,
                'entry_type' => $entryType,
                'amount' => $entryAmount,
                'description' => $type . ' transaction',
                'created_at' => $time,
            ]);

            $balanceChange = $entryType === 'credit' ? $entryAmount : -$entryAmount;
            $wallet->increment('balance', $balanceChange);
        }
    }
}
