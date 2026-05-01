<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $wallet = $user->wallet()->with('walletable')->firstOrFail();

        return response()->json([
            'balance' => $wallet->balance,
            'wallet_id' => $wallet->id,
            'type' => $wallet->type,
        ]);
    }

    public function transactions(Request $request)
    {
        $user = $request->user();
        // Get transactions where user’s personal wallet is involved (via ledger entries)
        $walletId = $user->wallet->id;

        $transactions = Transaction::whereHas('ledgerEntries', function ($q) use ($walletId) {
            $q->where('wallet_id', $walletId);
        })
        ->with(['ledgerEntries' => function ($q) use ($walletId) {
            $q->where('wallet_id', $walletId);
        }])
        ->latest()
        ->paginate(20);

        return response()->json($transactions);
    }
}
