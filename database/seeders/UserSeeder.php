<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // System user – owns the platform collection wallet
            [
                'name' => 'System Account',
                'phone' => '+254700000000',
                'email' => 'system@akiba.internal',
                'password' => Hash::make(Str::random(32)),
            ],
            // Real users
            [
                'name' => 'Jane Wanjiku',
                'phone' => '+254712345678',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Peter Mwangi',
                'phone' => '+254723456789',
                'email' => 'peter@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Mercy Chebet',
                'phone' => '+254734567890',
                'email' => null,
                'password' => null,
            ],
            [
                'name' => 'Brian Otieno',
                'phone' => '+254745678901',
                'email' => 'brian@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Faith Njeri',
                'phone' => '+254756789012',
                'email' => null,
                'password' => null,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);

            Wallet::create([
                'walletable_type' => User::class,
                'walletable_id' => $user->id,
                'type' => 'personal',
                'balance' => 0.00,
            ]);
        }
    }
}
