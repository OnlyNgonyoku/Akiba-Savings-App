<?php

namespace App\Filament\Resources\Fundraisers\Pages;

use App\Filament\Resources\Fundraisers\FundraiserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFundraiser extends EditRecord
{
    protected static string $resource = FundraiserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
