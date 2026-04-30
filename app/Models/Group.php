<?php

// app/Models/Group.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    protected $fillable = ['name', 'type', 'cycle_duration', 'contribution_amount', 'max_members', 'rules'];

    protected function casts(): array
    {
        return [
            'rules' => 'array',
            'contribution_amount' => 'decimal:2',
        ];
    }

    public function wallet(): MorphOne
    {
        return $this->morphOne(Wallet::class, 'walletable')->where('type', 'group');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_members')
                    ->withPivot('role', 'position', 'joined_at')
                    ->withTimestamps();
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class, 'goalable_id')
                    ->where('goals.goalable_type', 'App\Models\Group');
    }
}
