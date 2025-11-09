<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class ManageCountryCurrencies extends Page
{
    protected string $view = 'filament.pages.manage-country-currencies';
    public static function form(Schema $schema): Schema
{
    return $schema
        ->components([
            TextInput::make('country')->required(),
            TextInput::make('currency'),
            // ...
        ]);
}
}
