<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fundraiser;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FundraiserController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        return Fundraiser::where('status', 'active')->with('wallet')->paginate(20);
    }

    public function contribute(Request $request, Fundraiser $fundraiser)
    {
        $user = $request->user();
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);
        $amount = $validated['amount'];
        $userWallet = $user->wallet;
        $fundraiserWallet = $fundraiser->wallet;

        if ($userWallet->balance < $amount) {
            throw ValidationException::withMessages(['wallet' => 'Insufficient balance.']);
        }

        $transaction = $this->transactionService->process(
            type: 'contribution',
            amount: $amount,
            entries: [
                ['wallet_id' => $userWallet->id, 'entry_type' => 'debit', 'amount' => $amount],
                ['wallet_id' => $fundraiserWallet->id, 'entry_type' => 'credit', 'amount' => $amount],
            ],
            meta: [
                'initiator_type' => get_class($user),
                'initiator_id' => $user->id,
                'idempotency_key' => $request->header('Idempotency-Key'),
            ]
        );

        return response()->json([
            'message' => 'Contribution successful',
            'transaction_id' => $transaction->id,
            'reference' => $transaction->reference,
        ]);
    }
}
