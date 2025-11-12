<?php

namespace App\Filament\Resources\SourceFunds\Pages;

use App\Filament\Resources\SourceFunds\SourceFundsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSourceFunds extends ListRecords
{
    protected static string $resource = SourceFundsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
