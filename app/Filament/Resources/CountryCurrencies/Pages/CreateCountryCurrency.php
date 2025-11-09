<?php

namespace App\Filament\Resources\CountryCurrencies\Pages;

use App\Filament\Resources\CountryCurrencies\CountryCurrencyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCountryCurrency extends CreateRecord
{
    protected static string $resource = CountryCurrencyResource::class;
}
