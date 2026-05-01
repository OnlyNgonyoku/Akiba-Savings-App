<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Filament\Resources\Groups\GroupResource;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Personal Information')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            TextEntry::make('name')
                                ->label('Full name')
                                ->weight(FontWeight::Bold),
                            TextEntry::make('phone')
                                ->label('Phone number')
                                ->icon('heroicon-o-phone'),
                            TextEntry::make('email')
                                ->label('Email address')
                                ->placeholder('No email added'),
                            TextEntry::make('created_at')
                                ->label('Registered')
                                ->dateTime()
                                ->since(),
                        ]),
                ])
                ->collapsible(),

            Section::make('Wallet')
                ->schema([
                    TextEntry::make('wallet.balance')->money('KES')->weight(FontWeight::Bold),
                    TextEntry::make('wallet.created_at')->label('Wallet created')->dateTime(),
                ])
                ->columns(2),

            Section::make('Group Memberships')
                ->schema([
                    RepeatableEntry::make('groups')
                        ->label('')
                        ->schema([
                            TextEntry::make('name')
                                ->label('Group name')
                                ->url(fn ($record) => GroupResource::getUrl('view', ['record' => $record->id])),
                            TextEntry::make('pivot.role')
                                ->label('Role')
                                ->badge(),
                            TextEntry::make('pivot.position')
                                ->label('Position'),
                        ])
                        ->columns(3),
                ])
                ->collapsible()
                ->collapsed(),
        ]);
    }
}
