<?php

namespace App\Filament\Resources\Goals\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class GoalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
            Section::make('Goal Details')
                ->schema([
                    TextEntry::make('name')->weight(FontWeight::Bold),
                    TextEntry::make('goalable.name')->label('Owner'),
                    TextEntry::make('goalable_type')
                        ->label('Owner type')
                        ->formatStateUsing(fn ($state) => class_basename($state)),
                    TextEntry::make('target_amount')->money('KES'),
                    TextEntry::make('wallet.balance')->label('Saved so far')->money('KES'),
                    TextEntry::make('progress')
                        ->state(function ($record) {
                            $saved = $record->wallet?->balance ?? 0;
                            $target = $record->target_amount;
                            if ($target <= 0) return '0%';
                            return number_format(($saved / $target) * 100, 1) . '%';
                        }),
                    TextEntry::make('status')->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'active' => 'warning',
                            'completed' => 'success',
                            'cancelled' => 'danger',
                            default => 'gray',
                        }),
                    TextEntry::make('deadline')->date()->since(),
                ])->columns(3),
            ]);
    }
}
