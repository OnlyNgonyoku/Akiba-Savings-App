<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Goal extends Model
{
    protected $fillable = ['goalable_type', 'goalable_id', 'name', 'target_amount', 'deadline', 'status'];

    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'deadline' => 'datetime',
        ];
    }

    public function goalable(): MorphTo
    {
        return $this->morphTo();
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
