<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\FundraiserController;
use App\Http\Controllers\Api\V1\GoalController;
use App\Http\Controllers\Api\V1\GroupController;
use App\Http\Controllers\Api\V1\WalletController;
use App\Http\Controllers\Api\V1\WithdrawalController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->group(function () {
        // Public routes
        Route::post('auth/send-otp', [AuthController::class, 'sendOtp'])->middleware('throttle:3,1');
        Route::post('auth/verify-otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:10,1');

        Route::get('withdrawals', [WithdrawalController::class, 'index']);
        Route::post('withdrawals', [WithdrawalController::class, 'store'])->middleware('idempotency');

        // Protected routes (requires sanctum tokens)
        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::get('wallet', [WalletController::class, 'show']);
                Route::get('wallet/transactions', [WalletController::class, 'transactions']);

                Route::get('groups', [GroupController::class, 'index']);
                Route::get('groups/{group}', [GroupController::class, 'show']);
                Route::post('groups/{group}/contribute', [GroupController::class, 'contribute'])->middleware('idempotency');

                Route::get('goals', [GoalController::class, 'index']);
                Route::post('goals', [GoalController::class, 'store']);
                Route::get('goals/{goal}', [GoalController::class, 'show']);
                Route::post('goals/{goal}/deposit', [GoalController::class, 'deposit'])->middleware('idempotency');

                Route::get('fundraisers', [FundraiserController::class, 'index']);
                Route::post('fundraisers/{fundraiser}/contribute', [FundraiserController::class, 'contribute'])->middleware('idempotency');
        });
    });
