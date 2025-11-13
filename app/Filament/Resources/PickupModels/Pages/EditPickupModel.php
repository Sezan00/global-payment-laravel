<?php

namespace App\Filament\Resources\PickupModels\Pages;

use App\Filament\Resources\PickupModels\PickupModelResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPickupModel extends EditRecord
{
    protected static string $resource = PickupModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
