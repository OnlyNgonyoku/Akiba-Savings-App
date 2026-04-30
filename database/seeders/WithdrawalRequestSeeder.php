<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WithdrawalRequest;
use Illuminate\Database\Seeder;

class WithdrawalRequestSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $personalWallets = Wallet::where('type', 'personal')->get();

        foreach ($users as $index => $user) {
            if ($index >= 5) break;

            WithdrawalRequest::create([
                'wallet_id' => $personalWallets[$index]->id,
                'user_id' => $user->id,
                'amount' => rand(500, 5000),
                'destination' => $user->phone,
                'status' => $index < 3 ? 'pending' : 'completed',
                'approved_by' => $index < 3 ? null : User::where('id', '!=', $user->id)->first()?->id,
                'processed_at' => $index < 3 ? null : now(),
            ]);
        }
    }
}
