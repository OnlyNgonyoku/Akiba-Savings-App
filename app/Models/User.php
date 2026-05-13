<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Allow access to Filament panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'walletable_id')
            ->where('wallets.walletable_type', self::class)
            ->where('wallets.type', 'personal');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_members')
            ->withPivot('role', 'position', 'joined_at')
            ->withTimestamps();
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class, 'goalable_id')
            ->where('goals.goalable_type', self::class);
    }

    public function fundraisers(): HasMany
    {
        return $this->hasMany(Fundraiser::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'initiator_id')
            ->where('transactions.initiator_type', self::class);
    }

    public function withdrawalRequests(): HasMany
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
