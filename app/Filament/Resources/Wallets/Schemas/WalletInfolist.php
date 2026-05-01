<?php

namespace App\Filament\Resources\Wallets\Schemas;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class WalletInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            Section::make('Wallet Details')
                ->schema([
                    Grid::make(3)
                        ->schema([
                            TextEntry::make('id')
                                ->label('Wallet ID'),
                            TextEntry::make('type')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'personal' => 'success',
                                    'group' => 'info',
                                    'goal_escrow' => 'warning',
                                    'fundraiser' => 'primary',
                                    'system_collection' => 'gray',
                                    default => 'gray',
                                }),
                            TextEntry::make('balance')
                                ->money('KES')
                                ->weight(FontWeight::Bold),
                        ]),
                ]),

            Section::make('Owner')
                ->schema([
                    TextEntry::make('walletable_type')
                        ->label('Owner type')
                        ->formatStateUsing(fn ($state) => class_basename($state)),
                    TextEntry::make('walletable.name')
                        ->label('Owner name')
                        ->url(function ($record) {
                            $type = $record->walletable_type;
                            $id = $record->walletable_id;
                            if ($type === User::class) {
                                return UserResource::getUrl('view', ['record' => $id]);
                            }
                            // add other links as needed
                            return null;
                        }),
                ])
                ->columns(2),

            Section::make('Ledger History')
                ->schema([
                    TextEntry::make('ledgerEntries')
                        ->label('Total transactions')
                        ->state(fn ($record) => $record->ledgerEntries()->count()),
                ]),
            ]);
    }
}
