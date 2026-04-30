<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $wallet = Wallet::where('type', 'personal')->first();

        $logs = [
            ['event' => 'created', 'auditable_type' => User::class, 'auditable_id' => $user->id, 'old' => null, 'new' => $user->toArray()],
            ['event' => 'updated', 'auditable_type' => User::class, 'auditable_id' => $user->id, 'old' => ['name' => 'Old Name'], 'new' => ['name' => 'Jane Wanjiku']],
            ['event' => 'login', 'auditable_type' => User::class, 'auditable_id' => $user->id, 'old' => null, 'new' => ['logged_in_at' => now()]],
            ['event' => 'wallet_credited', 'auditable_type' => Wallet::class, 'auditable_id' => $wallet->id, 'old' => null, 'new' => ['amount' => 5000]],
            ['event' => 'withdrawal_requested', 'auditable_type' => Wallet::class, 'auditable_id' => $wallet->id, 'old' => null, 'new' => ['amount' => 1000, 'status' => 'pending']],
        ];

        foreach ($logs as $log) {
            AuditLog::create([
                'user_id' => $user->id,
                'event' => $log['event'],
                'auditable_type' => $log['auditable_type'],
                'auditable_id' => $log['auditable_id'],
                'old_values' => $log['old'] ? json_encode($log['old']) : null,
                'new_values' => json_encode($log['new']),
                'created_at' => now()->subMinutes(rand(1, 60)),
            ]);
        }
    }
}
