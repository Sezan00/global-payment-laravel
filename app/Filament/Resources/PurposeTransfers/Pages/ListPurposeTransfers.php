<?php

namespace App\Filament\Resources\PurposeTransfers\Pages;

use App\Filament\Resources\PurposeTransfers\PurposeTransferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPurposeTransfers extends ListRecords
{
    protected static string $resource = PurposeTransferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
