<?php

// app/Models/Fundraiser.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fundraiser extends Model
{
    protected $fillable = ['user_id', 'title', 'target_amount', 'deadline', 'status'];

    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'deadline' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
