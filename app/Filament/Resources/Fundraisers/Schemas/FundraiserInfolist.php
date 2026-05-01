<?php

// app/Filament/Resources/Fundraisers/Schemas/FundraiserInfolist.php
namespace App\Filament\Resources\Fundraisers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class FundraiserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Fundraiser Details')
                    ->schema([
                        TextEntry::make('title')->weight(FontWeight::Bold),
                        TextEntry::make('creator.name')->label('Created by'),
                        TextEntry::make('target_amount')->money('KES'),
                        TextEntry::make('wallet.balance')->label('Amount raised')->money('KES'),
                        TextEntry::make('progress')
                            ->state(function ($record) {
                                $raised = $record->wallet?->balance ?? 0;
                                $target = $record->target_amount;
                                if ($target <= 0) return '0%';
                                return number_format(($raised / $target) * 100, 1) . '%';
                            }),
                        TextEntry::make('deadline')->date()->since(),
                        TextEntry::make('status')->badge(),
                    ])
                    ->columns(3),
        ]);
    }
}
