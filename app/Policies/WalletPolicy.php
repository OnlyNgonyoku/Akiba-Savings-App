<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\Response;

class WalletPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Wallet $wallet): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Wallet $wallet): bool
    {
        return true;
    }

    public function delete(User $user, Wallet $wallet): bool
    {
        return false; // Wallets should never be deleted directly
    }
}
