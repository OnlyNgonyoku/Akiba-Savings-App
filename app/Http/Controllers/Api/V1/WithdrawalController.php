<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $requests = $user->withdrawalRequests()->latest()->paginate(20);
        return response()->json($requests);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'destination' => 'required|string|max:255',
        ]);

        $wallet = $user->wallet;
        if ($wallet->balance < $validated['amount']) {
            throw ValidationException::withMessages(['amount' => 'Insufficient balance.']);
        }

        $withdrawal = WithdrawalRequest::create([
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
            'amount' => $validated['amount'],
            'destination' => $validated['destination'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Withdrawal request submitted',
            'id' => $withdrawal->id,
            'status' => $withdrawal->status,
        ], 201);
    }
}
