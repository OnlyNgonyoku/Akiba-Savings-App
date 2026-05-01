<?php

namespace App\Filament\Resources\Fundraisers\Pages;

use App\Filament\Resources\Fundraisers\FundraiserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFundraiser extends ViewRecord
{
    protected static string $resource = FundraiserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
