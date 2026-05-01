<?php

namespace App\Filament\Resources\Transactions;

use App\Filament\Resources\Transactions\Pages\CreateTransaction;
use App\Filament\Resources\Transactions\Pages\EditTransaction;
use App\Filament\Resources\Transactions\Pages\ListTransactions;
use App\Filament\Resources\Transactions\Pages\ViewTransaction;
use App\Filament\Resources\Transactions\RelationManagers\LedgerEntriesRelationManager;
use App\Filament\Resources\Transactions\Schemas\TransactionForm;
use App\Filament\Resources\Transactions\Schemas\TransactionInfolist;
use App\Filament\Resources\Transactions\Tables\TransactionsTable;
use App\Models\Transaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    // Cannot create transactions from the admin panel, as they are created through the application logic
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return TransactionForm::configure($schema);
    }
    public static function infolist(Schema $schema): Schema
    {
        return TransactionInfolist::configure($schema);
    }
    public static function table(Table $table): Table
    {
        return TransactionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
            LedgerEntriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTransactions::route('/'),
            // 'edit' => EditTransaction::route('/{record}/edit'),
            'view' => ViewTransaction::route('/{record}'),
        ];
    }
}
