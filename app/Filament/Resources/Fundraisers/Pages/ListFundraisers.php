<?php

namespace App\Filament\Resources\Fundraisers\Pages;

use App\Filament\Resources\Fundraisers\FundraiserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFundraisers extends ListRecords
{
    protected static string $resource = FundraiserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
