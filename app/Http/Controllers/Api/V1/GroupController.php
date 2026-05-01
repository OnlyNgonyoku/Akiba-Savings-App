<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $groups = $user->groups()->with('wallet')->get();

        return response()->json($groups);
    }

    public function show(Group $group)
    {
        $group->load(['members', 'wallet']);
        return response()->json($group);
    }

    public function contribute(Request $request, Group $group)
    {
        $user = $request->user();

        // Validate membership and contribution amount
        if (!$group->members()->where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages(['group' => 'You are not a member of this group.']);
        }

        $amount = $group->contribution_amount;
        $userWallet = $user->wallet;
        $groupWallet = $group->wallet;

        // Balance check
        if ($userWallet->balance < $amount) {
            throw ValidationException::withMessages(['wallet' => 'Insufficient balance.']);
        }

        // Process double-entry
        $transaction = $this->transactionService->process(
            type: 'contribution',
            amount: $amount,
            entries: [
                ['wallet_id' => $userWallet->id, 'entry_type' => 'debit', 'amount' => $amount],
                ['wallet_id' => $groupWallet->id, 'entry_type' => 'credit', 'amount' => $amount],
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
        ], 201);
    }
}
