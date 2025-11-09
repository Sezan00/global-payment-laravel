<?php

namespace App\Filament\Resources\CountryCurrencies\Pages;

use App\Filament\Resources\CountryCurrencies\CountryCurrencyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCountryCurrencies extends ListRecords
{
    protected static string $resource = CountryCurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
