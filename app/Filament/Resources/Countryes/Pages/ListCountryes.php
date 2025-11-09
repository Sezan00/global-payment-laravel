<?php

namespace App\Filament\Resources\Countryes\Pages;

use App\Filament\Resources\Countryes\CountryeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCountryes extends ListRecords
{
    protected static string $resource = CountryeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
