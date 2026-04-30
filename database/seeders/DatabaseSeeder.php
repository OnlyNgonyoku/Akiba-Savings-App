<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::createOrFirst([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'phone' => '254707670113',
            'password' => bcrypt('password123'),
        ]);
        $this->call([
            UserSeeder::class,
            GroupSeeder::class,
            FundraiserSeeder::class,
            GoalSeeder::class,
            TransactionSeeder::class,       // Creates ledger entries internally
            WithdrawalRequestSeeder::class,
            AuditLogSeeder::class,
        ]);
        // User::factory()->create([
        //     'name' => 'Super Admin',
        //     'email' => 'test@example.com',
        //     'phone' => '254707670113',
        // ]);
        // User::factory(49)->create();
    }
}
