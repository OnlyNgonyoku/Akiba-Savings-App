<?php

namespace App\Filament\Resources\GroupResource\RelationManagers;

use App\Models\User;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';
    protected static ?string $title = 'Group Members';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('pivot.role')
                    ->label('Role')
                    ->badge(),
                Tables\Columns\TextColumn::make('pivot.position')
                    ->label('Position'),
                Tables\Columns\TextColumn::make('pivot.joined_at')
                    ->label('Joined')
                    ->dateTime(),
            ])
            ->filters([])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('role')
                            ->options([
                                'admin' => 'Admin',
                                'member' => 'Member',
                            ])
                            ->default('member')
                            ->required(),
                        TextInput::make('position')
                            ->numeric()
                            ->nullable(),
                    ]),
            ])
            ->actions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('role')
                            ->options([
                                'admin' => 'Admin',
                                'member' => 'Member',
                            ])
                            ->default('member')
                            ->required(),
                        TextInput::make('position')
                            ->numeric()
                            ->nullable(),
                    ]),
                EditAction::make()
                    ->form([
                        Select::make('role')
                            ->options(['admin' => 'Admin', 'member' => 'Member'])
                            ->required(),
                        TextInput::make('position')
                            ->numeric()
                            ->nullable(),
                    ]),
                DetachAction::make(),
            ])
            ->bulkActions([
                DetachBulkAction::make(),
            ]);
    }
}
