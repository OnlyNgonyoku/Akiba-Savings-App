<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Transaction Details')
                    ->schema([
                    TextEntry::make('reference')->weight(FontWeight::Bold),
                    TextEntry::make('type')->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'deposit' => 'success',
                            'contribution' => 'info',
                            'transfer' => 'primary',
                            'payout' => 'warning',
                            'withdrawal' => 'danger',
                            'fee' => 'gray',
                            default => 'gray',
                        }),
                    TextEntry::make('amount')->money('KES'),
                    TextEntry::make('status')->badge(),
                    TextEntry::make('initiator.name')->label('Initiated by'),
                    TextEntry::make('created_at')->label('Date')->dateTime(),
                ])->columns(3),

            Section::make('Idempotency & Metadata')
                ->schema([
                    TextEntry::make('idempotency_key')
                        ->label('Idempotency Key')
                        ->copyable()
                        ->visible(fn ($state) => filled($state)),
                    KeyValueEntry::make('metadata')
                        ->label('Additional Data'),
                ])
                ->collapsible(),

            Section::make('Ledger Entries')
                ->schema([
                    TextEntry::make('ledgerEntries')
                        ->label('Double‑entry breakdown')
                        ->state(fn ($record) => $record->ledgerEntries->map(fn ($e) => "{$e->entry_type}: {$e->amount} KES → wallet #{$e->wallet_id}")->join("\n")),
                ]),
        ]);
    }
}
