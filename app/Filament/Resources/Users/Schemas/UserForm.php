<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->description('Enter the user’s basic details')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Full name')
                                    ->placeholder('e.g. John Doe')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-o-user')
                                    ->helperText('Your legal full name'),

                                TextInput::make('phone')
                                    ->label('Phone number')
                                    ->tel()
                                    ->placeholder('e.g. +254712345678')
                                    ->maxLength(20)
                                    ->prefixIcon('heroicon-o-phone')
                                    ->helperText('Include country code'),
                            ]),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->placeholder('e.g. john.doe@example.com')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-o-envelope')
                            ->hint('Will be used for notifications and login'),
                        // Password field with read-only on edit
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->rule(Password::defaults())
                            ->required(fn ($operation) => $operation === 'create')
                            ->readOnly(fn ($operation) => $operation === 'edit') // NEW: read-only on edit
                            ->dehydrated(fn ($state) => filled($state))
                            ->helperText(fn ($operation) =>
                                $operation === 'edit'
                                    ? 'Password cannot be changed from this form. Contact administrator to reset it.'
                                    : 'Minimum 8 characters. Use a strong password.'
                            )
                            ->prefixIcon('heroicon-o-key'),
                    ])
                    ->collapsible()
                    ->collapsed(false),
            ]);
    }
}
