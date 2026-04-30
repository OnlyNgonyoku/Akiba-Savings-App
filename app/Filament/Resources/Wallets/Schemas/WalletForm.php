<?php

namespace App\Filament\Resources\Wallets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WalletForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Select::make('walletable_type')
                    ->label('Owner Type')
                    ->options([
                        'App\Models\User' => 'User',
                        'App\Models\Group' => 'Group',
                        'App\Models\Fundraiser' => 'Fundraiser',
                        'App\Models\Goal' => 'Goal',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('walletable_id', null)),

                TextInput::make('walletable_id')
                    ->label('Owner ID')
                    ->numeric()
                    ->required(),

                Select::make('type')
                    ->label('Wallet Type')
                    ->options([
                        'personal' => 'Personal',
                        'group' => 'Group',
                        'goal_escrow' => 'Goal Escrow',
                        'fundraiser' => 'Fundraiser',
                        'system_collection' => 'System Collection',
                    ])
                    ->required(),

                TextInput::make('balance')
                    ->label('Initial Balance (KES)')
                    ->numeric()
                    ->default(0.00)
                    ->required(),
            ]);
    }
}
