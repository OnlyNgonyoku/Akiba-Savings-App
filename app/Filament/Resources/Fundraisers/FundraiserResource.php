<?php

namespace App\Filament\Resources\Fundraisers;

use App\Filament\Resources\Fundraisers\Pages\CreateFundraiser;
use App\Filament\Resources\Fundraisers\Pages\EditFundraiser;
use App\Filament\Resources\Fundraisers\Pages\ListFundraisers;
use App\Filament\Resources\Fundraisers\Schemas\FundraiserForm;
use App\Filament\Resources\Fundraisers\Tables\FundraisersTable;
use App\Models\Fundraiser;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FundraiserResource extends Resource
{
    protected static ?string $model = Fundraiser::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    public static function form(Schema $schema): Schema
    {
        return FundraiserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FundraisersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFundraisers::route('/'),
            'create' => CreateFundraiser::route('/create'),
            'edit' => EditFundraiser::route('/{record}/edit'),
        ];
    }
}
