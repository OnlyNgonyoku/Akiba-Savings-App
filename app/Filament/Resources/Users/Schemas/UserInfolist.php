<?php

namespace App\Filament\Resources\Users\Schemas;


use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Carbon;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('User Information')
                ->schema([
                    TextEntry::make('id')
                        ->label('ID')
                        ->copyable()
                        ->copyMessage('User ID copied'),

                    TextEntry::make('name')
                        ->label('Full name')
                        ->size(TextSize::Large),

                    TextEntry::make('email')
                        ->label('Email address')
                        ->copyable()
                        ->icon('heroicon-o-envelope'),

                    TextEntry::make('email_verified_at')
                        ->label('Email verified')
                        ->dateTime()
                        ->badge()
                        ->color(fn (?Carbon $state) => $state ? 'success' : 'danger')
                        ->formatStateUsing(fn (?Carbon $state) => $state ? $state->toFormattedDateString() : 'Not verified'),
                ])->columns(2),

            Section::make('Timestamps')
                ->schema([
                    TextEntry::make('created_at')
                        ->label('Created')
                        ->dateTime()
                        ->since(),

                    TextEntry::make('updated_at')
                        ->label('Last updated')
                        ->dateTime()
                        ->since(),
                ])->columns(2),
        ]);
    }
}
