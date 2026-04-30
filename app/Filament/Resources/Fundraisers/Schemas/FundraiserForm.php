<?php

namespace App\Filament\Resources\Fundraisers\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FundraiserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Select::make('user_id')
                    ->label('Creator')
                    ->options(User::pluck('name', 'id'))
                    ->required()
                    ->searchable(),

                TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                TextInput::make('target_amount')
                    ->numeric()
                    ->required()
                    ->prefix('KES'),

                DateTimePicker::make('deadline')
                    ->required(),

                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'closed' => 'Closed',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }
}
