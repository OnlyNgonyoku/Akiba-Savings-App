<?php

namespace App\Services;

use App\Models\LedgerEntry;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionService
{
    /**
     * Process a financial transaction with double-entry.
     *
     * @param string $type         deposit|contribution|transfer|payout|withdrawal
     * @param float  $amount
     * @param array  $entries      [[ 'wallet_id' => X, 'entry_type' => 'debit', 'amount' => Y ], ...]
     * @param array  $meta         initiator_type, initiator_id, idempotency_key, metadata
     * @return Transaction
     * @throws \Exception
     */
    public function process(string $type, float $amount, array $entries, array $meta): Transaction
    {
        // Ensure entries sum to zero
        $sum = 0;
        foreach ($entries as $e) {
            $sum += $e['entry_type'] === 'credit' ? $e['amount'] : -$e['amount'];
        }
        if (abs($sum) > 0.0001) {
            throw new \Exception('Ledger entries must balance (debits = credits).');
        }

        return DB::transaction(function () use ($type, $amount, $entries, $meta) {
            // Lock all involved wallets to prevent race conditions
            $walletIds = array_column($entries, 'wallet_id');
            $wallets = Wallet::whereIn('id', $walletIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Ensure all wallets exist
            foreach ($walletIds as $id) {
                if (!isset($wallets[$id])) {
                    throw new \Exception("Wallet {$id} not found.");
                }
            }

            // Create the transaction record (idempotency key already verified)
            $transaction = Transaction::create([
                'initiator_type' => $meta['initiator_type'] ?? null,
                'initiator_id' => $meta['initiator_id'] ?? null,
                'type' => $type,
                'amount' => $amount,
                'reference' => 'TXN-' . strtoupper(Str::random(10)),
                'idempotency_key' => $meta['idempotency_key'] ?? null,
                'status' => 'completed',
                'metadata' => $meta['metadata'] ?? null,
            ]);

            // Create ledger entries and update wallet balances
            foreach ($entries as $entry) {
                $wallet = $wallets[$entry['wallet_id']];
                LedgerEntry::create([
                    'transaction_id' => $transaction->id,
                    'wallet_id' => $wallet->id,
                    'entry_type' => $entry['entry_type'],
                    'amount' => $entry['amount'],
                    'description' => $entry['description'] ?? $type,
                ]);

                // Update cached balance (debit reduces, credit increases)
                $change = $entry['entry_type'] === 'credit' ? $entry['amount'] : -$entry['amount'];
                $wallet->increment('balance', $change);
            }

            return $transaction;
        });
    }
}
