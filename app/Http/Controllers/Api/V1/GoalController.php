<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Wallet;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GoalController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        // Personal and group goals where user is a member
        $personalGoals = $user->goals()->with('wallet')->get();
        $groupGoals = Goal::whereHasMorph('goalable', [\App\Models\Group::class], function ($q) use ($user) {
            $q->whereHas('members', fn ($q) => $q->where('user_id', $user->id));
        })->with('wallet')->get();

        return response()->json([
            'personal' => $personalGoals,
            'group' => $groupGoals,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'deadline' => 'nullable|date',
        ]);

        $user = $request->user();

        // Create goal with lockbox wallet
        $wallet = Wallet::create([
            'walletable_type' => Goal::class,
            'walletable_id' => 0, // placeholder
            'type' => 'goal_escrow',
            'balance' => 0,
        ]);

        $goal = Goal::create([
            'goalable_type' => get_class($user),
            'goalable_id' => $user->id,
            'name' => $validated['name'],
            'target_amount' => $validated['target_amount'],
            'deadline' => $validated['deadline'] ?? null,
            'wallet_id' => $wallet->id,
        ]);

        $wallet->update(['walletable_id' => $goal->id]);

        return response()->json($goal->load('wallet'), 201);
    }

    public function show(Goal $goal)
    {
        $goal->load('wallet');
        return response()->json($goal);
    }

    public function deposit(Request $request, Goal $goal)
    {
        $user = $request->user();
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);
        $amount = $validated['amount'];
        $userWallet = $user->wallet;
        $goalWallet = $goal->wallet;

        if ($userWallet->balance < $amount) {
            throw ValidationException::withMessages(['wallet' => 'Insufficient balance.']);
        }

        $transaction = $this->transactionService->process(
            type: 'transfer',
            amount: $amount,
            entries: [
                ['wallet_id' => $userWallet->id, 'entry_type' => 'debit', 'amount' => $amount],
                ['wallet_id' => $goalWallet->id, 'entry_type' => 'credit', 'amount' => $amount],
            ],
            meta: [
                'initiator_type' => get_class($user),
                'initiator_id' => $user->id,
                'idempotency_key' => $request->header('Idempotency-Key'),
                'metadata' => json_encode(['goal_id' => $goal->id]),
            ]
        );

        return response()->json([
            'message' => 'Deposit to goal successful',
            'transaction_id' => $transaction->id,
            'reference' => $transaction->reference,
        ], 200);
    }
}
