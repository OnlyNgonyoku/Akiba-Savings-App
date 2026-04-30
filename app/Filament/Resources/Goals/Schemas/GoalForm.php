<?php

namespace App\Filament\Resources\Goals\Schemas;

use App\Models\Group;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GoalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Select::make('goalable_type')
                    ->label('Owner Type')
                    ->options([
                        'App\Models\User' => 'User',
                        'App\Models\Group' => 'Group',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('goalable_id', null)),

                Select::make('goalable_id')
                    ->label('Owner')
                    ->options(function (callable $get) {
                        $type = $get('goalable_type');
                        if ($type === 'App\Models\User') {
                            return User::pluck('name', 'id');
                        } elseif ($type === 'App\Models\Group') {
                            return Group::pluck('name', 'id');
                        }
                        return [];
                    })
                    ->required(),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('target_amount')
                    ->numeric()
                    ->required()
                    ->prefix('KES'),

                DateTimePicker::make('deadline')
                    ->nullable(),

                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }
}
