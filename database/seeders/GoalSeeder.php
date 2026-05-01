<?php

namespace Database\Seeders;

use App\Models\Goal;
use App\Models\Group;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class GoalSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $groups = Group::all();

        $goals = [
            // Personal goals
            [
                'goalable_type' => User::class,
                'goalable_id' => $users->random()->id,
                'name' => 'New Laptop',
                'target_amount' => 30000.00,
                'deadline' => now()->addMonths(3),
                'status' => 'active',
            ],
            [
                'goalable_type' => User::class,
                'goalable_id' => $users->random()->id,
                'name' => 'Rent Deposit',
                'target_amount' => 20000.00,
                'deadline' => now()->addMonths(2),
                'status' => 'active',
            ],
                        [
                'goalable_type' => User::class,
                'goalable_id' => $users->random()->id,
                'name' => 'Rumuruti 5Acre Farm',
                'target_amount' => 300000.00,
                'deadline' => now()->addMonths(6),
                'status' => 'active',
            ],
            [
                'goalable_type' => User::class,
                'goalable_id' => $users->random()->id,
                'name' => 'Mom\'s 60th Birthday',
                'target_amount' => 20000.00,
                'deadline' => now()->addMonths(2),
                'status' => 'active',
            ],


            // Group goals
            [
                'goalable_type' => Group::class,
                'goalable_id' => $groups->random()->id,
                'name' => 'Group Emergency Fund',
                'target_amount' => 50000.00,
                'deadline' => now()->addYear(),
                'status' => 'active',
            ],
            [
                'goalable_type' => Group::class,
                'goalable_id' => $groups->random()->id,
                'name' => 'Investment Pool',
                'target_amount' => 100000.00,
                'deadline' => now()->addMonths(6),
                'status' => 'active',
            ],
                        [
                'goalable_type' => Group::class,
                'goalable_id' => $groups->random()->id,
                'name' => 'Community Health Fund',
                'target_amount' => 50000.00,
                'deadline' => now()->addYear(),
                'status' => 'active',
            ],
            [
                'goalable_type' => Group::class,
                'goalable_id' => $groups->random()->id,
                'name' => 'Farmer\'s Investment Pool',
                'target_amount' => 100000.00,
                'deadline' => now()->addMonths(6),
                'status' => 'active',
            ],
                        [
                'goalable_type' => Group::class,
                'goalable_id' => $groups->random()->id,
                'name' => 'Community Education Fund',
                'target_amount' => 50000.00,
                'deadline' => now()->addYear(),
                'status' => 'active',
            ],
            [
                'goalable_type' => Group::class,
                'goalable_id' => $groups->random()->id,
                'name' => 'Youth Entrepreneurship Fund',
                'target_amount' => 100000.00,
                'deadline' => now()->addMonths(6),
                'status' => 'active',
            ],
            // Another personal goal
            [
                'goalable_type' => User::class,
                'goalable_id' => $users->random()->id,
                'name' => 'Vacation Fund',
                'target_amount' => 25000.00,
                'deadline' => now()->addMonths(4),
                'status' => 'active',
            ],
        ];

        foreach ($goals as $data) {
            // Create wallet
            $wallet = Wallet::create([
                'walletable_type' => Goal::class,
                'walletable_id' => 0, // placeholder
                'type' => 'goal_escrow',
                'balance' => 0.00,
            ]);

            $goal = Goal::create([
                'goalable_type' => $data['goalable_type'],
                'goalable_id' => $data['goalable_id'],
                'name' => $data['name'],
                'target_amount' => $data['target_amount'],
                'deadline' => $data['deadline'],
                'status' => $data['status'],
                'wallet_id' => $wallet->id,
            ]);

            $wallet->update([
                'walletable_type' => Goal::class,
                'walletable_id' => $goal->id,
            ]);
        }
    }
}
