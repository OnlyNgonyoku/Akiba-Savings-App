<?php

namespace Database\Seeders;

use App\Models\Fundraiser;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class FundraiserSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $fundraisers = [
            [
                'title' => 'Medical Emergency for Baby Amani',
                'target_amount' => 50000.00,
                'deadline' => now()->addDays(15),
            ],
            [
                'title' => 'School Fees Drive for 10 Kids',
                'target_amount' => 100000.00,
                'deadline' => now()->addDays(30),
            ],
            [
                'title' => 'Community Borehole Project',
                'target_amount' => 200000.00,
                'deadline' => now()->addDays(45),
            ],
            [
                'title' => 'Funeral Support for John’s Family',
                'target_amount' => 30000.00,
                'deadline' => now()->addDays(7),
            ],
            [
                'title' => 'Startup Capital for Mama Mboga',
                'target_amount' => 15000.00,
                'deadline' => now()->addDays(10),
            ],
        ];

        foreach ($fundraisers as $data) {
            $creator = $users->random();

            // Create wallet first
            $wallet = Wallet::create([
                'walletable_type' => Fundraiser::class,
                'walletable_id' => 0,  // temporary, will update after fundraiser created
                'type' => 'fundraiser',
                'balance' => 0.00,
            ]);

            $fundraiser = Fundraiser::create([
                'user_id' => $creator->id,
                'title' => $data['title'],
                'target_amount' => $data['target_amount'],
                'deadline' => $data['deadline'],
                'status' => 'active',
                'wallet_id' => $wallet->id,
            ]);

            // Update wallet's polymorphic ownership
            $wallet->update([
                'walletable_type' => Fundraiser::class,
                'walletable_id' => $fundraiser->id,
            ]);
        }
    }
}
