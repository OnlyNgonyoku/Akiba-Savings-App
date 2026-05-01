<?php

// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'initiator_type',
        'initiator_id',
        'type',
        'amount',
        'reference',
        'idempotency_key',
        'status',
        'metadata',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    public function initiator(): MorphTo
    {
        return $this->morphTo();
    }

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }
}
