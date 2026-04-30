<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure users exist
        $users = User::all();

        $groups = [
            [
                'name' => 'Kibera Women Chama',
                'type' => 'rotational',
                'cycle_duration' => 30,
                'contribution_amount' => 500.00,
                'max_members' => 5,
                'rules' => json_encode(['payout_order' => 'random', 'penalty' => 50]),
            ],
            [
                'name' => 'Nairobi Youth Savings',
                'type' => 'milestone',
                'contribution_amount' => 1000.00,
                'max_members' => 10,
            ],
            [
                'name' => 'Eldoret Investment Club',
                'type' => 'open',
                'contribution_amount' => 2000.00,
                'max_members' => 20,
            ],
            [
                'name' => 'Mombasa Beach Chama',
                'type' => 'rotational',
                'cycle_duration' => 60,
                'contribution_amount' => 300.00,
                'max_members' => 7,
            ],
            [
                'name' => 'Techies Savings Group',
                'type' => 'milestone',
                'contribution_amount' => 5000.00,
                'max_members' => 15,
            ],
        ];

        foreach ($groups as $index => $groupData) {
            $group = Group::create($groupData);

            // Create wallet for the group
            Wallet::create([
                'walletable_type' => Group::class,
                'walletable_id' => $group->id,
                'type' => 'group',
                'balance' => 0.00,
            ]);

            // Attach 2-3 random members to each group
            $members = $users->random(rand(2, 3))->pluck('id')->toArray();
            foreach ($members as $pos => $userId) {
                $group->members()->attach($userId, [
                    'role' => $pos === 0 ? 'admin' : 'member',
                    'position' => $pos + 1,
                    'joined_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
