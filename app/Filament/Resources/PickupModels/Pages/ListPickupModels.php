<?php

namespace App\Filament\Resources\PickupModels\Pages;

use App\Filament\Resources\PickupModels\PickupModelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPickupModels extends ListRecords
{
    protected static string $resource = PickupModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
