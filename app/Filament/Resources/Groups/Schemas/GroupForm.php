<?php

namespace App\Filament\Resources\Groups\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->options([
                        'rotational' => 'Rotational',
                        'milestone' => 'Milestone-based',
                        'open' => 'Open',
                    ])
                    ->required()
                    ->reactive(),
                TextInput::make('contribution_amount')
                    ->numeric()
                    ->required()
                    ->prefix('KES'),
                TextInput::make('cycle_duration')
                    ->numeric()
                    ->label('Cycle Duration (days)')
                    ->visible(fn (callable $get) => $get('type') === 'rotational')
                    ->nullable(),
                TextInput::make('max_members')
                    ->numeric()
                    ->nullable(),
                TextInput::make('rules')
                    ->json()
                    ->nullable(),
            ]);
    }
}
