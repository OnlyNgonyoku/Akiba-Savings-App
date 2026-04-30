<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = ['walletable_type', 'walletable_id', 'type', 'balance'];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
        ];
    }

    public function walletable(): MorphTo
    {
        return $this->morphTo();
    }

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }

    // The true balance is derived from ledger entries, but we can provide a helper
    public function recalculateBalance(): float
    {
        return $this->ledgerEntries()
                    ->selectRaw("SUM(CASE WHEN entry_type = 'credit' THEN amount ELSE -amount END) as balance")
                    ->value('balance') ?? 0.00;
    }
}
