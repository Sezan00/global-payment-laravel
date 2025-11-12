<?php

namespace App\Filament\Resources\PickupMethods\Pages;

use App\Filament\Resources\PickupMethods\PickupMethodResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPickupMethod extends EditRecord
{
    protected static string $resource = PickupMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
