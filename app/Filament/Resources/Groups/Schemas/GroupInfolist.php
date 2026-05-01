<?php

namespace App\Filament\Resources\Groups\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class GroupInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Group Info')
                ->schema([
                    TextEntry::make('name')->weight(FontWeight::Bold),
                    TextEntry::make('type')->badge(),
                    TextEntry::make('contribution_amount')->money('KES'),
                    TextEntry::make('cycle_duration')->label('Cycle (days)')->placeholder('N/A'),
                    TextEntry::make('max_members')->placeholder('Unlimited'),
                    TextEntry::make('rules')
                        ->label('Rules')
                        ->formatStateUsing(fn ($state) => $state ? json_encode(json_decode($state), JSON_PRETTY_PRINT) : '—'),
                ])->columns(3),

            Section::make('Financials')
                ->schema([
                    TextEntry::make('wallet.balance')->money('KES')->weight(FontWeight::Bold),
                    TextEntry::make('wallet.created_at')->label('Wallet created')->dateTime(),
                ])->columns(2),

            Section::make('Members')
                ->schema([
                    RepeatableEntry::make('members')
                        ->label('')
                        ->schema([
                            TextEntry::make('name')->label('Member name'),
                            TextEntry::make('pivot.role')->badge(),
                            TextEntry::make('pivot.position'),
                            TextEntry::make('pivot.joined_at')->dateTime(),
                        ])
                        ->columns(4),
                ])
                ->collapsible(),
        ]);
    }
}
