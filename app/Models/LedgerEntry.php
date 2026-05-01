<?php

// app/Models/LedgerEntry.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LedgerEntry extends Model
{
    use SoftDeletes;
    public $timestamps = false;   // only created_at is set; updated_at not needed
    protected $fillable = ['transaction_id', 'wallet_id', 'entry_type', 'amount', 'description'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
