<?php

namespace App\Filament\Resources\CountryCurrencies\Pages;

use App\Filament\Resources\CountryCurrencies\CountryCurrencyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCountryCurrency extends EditRecord
{
    protected static string $resource = CountryCurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
