<?php

namespace App\Filament\Resources\Countryes\Pages;

use App\Filament\Resources\Countryes\CountryeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCountrye extends EditRecord
{
    protected static string $resource = CountryeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
