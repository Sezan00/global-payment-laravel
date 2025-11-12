<?php

namespace App\Filament\Resources\PickupMethods\Pages;

use App\Filament\Resources\PickupMethods\PickupMethodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPickupMethods extends ListRecords
{
    protected static string $resource = PickupMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
