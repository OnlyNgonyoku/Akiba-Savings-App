<?php

namespace App\Filament\Resources\WithdrawalRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;

class WithdrawalRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            Section::make('Request Details')
                ->schema([
                    TextEntry::make('user.name')->label('Requested by'),
                    TextEntry::make('amount')->money('KES')->weight(FontWeight::Bold),
                    TextEntry::make('destination')->label('Destination'),
                    TextEntry::make('status')->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            'completed' => 'info',
                            default => 'gray',
                        }),
                    TextEntry::make('approvedBy.name')->label('Approved / Rejected by')->placeholder('Not yet'),
                    TextEntry::make('created_at')->label('Requested')->dateTime(),
                    TextEntry::make('processed_at')->label('Processed at')->dateTime()->placeholder('—'),
                ])
                ->columns(3),
        ]);
    }
}
