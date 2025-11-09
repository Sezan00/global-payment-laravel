<?php

namespace App\Filament\Resources\CountryCurrencies;

use App\Filament\Resources\CountryCurrencies\Pages\CreateCountryCurrency;
use App\Filament\Resources\CountryCurrencies\Pages\EditCountryCurrency;
use App\Filament\Resources\CountryCurrencies\Pages\ListCountryCurrencies;
use App\Filament\Resources\CountryCurrencies\Schemas\CountryCurrencyForm;
use App\Filament\Resources\CountryCurrencies\Tables\CountryCurrenciesTable;
use App\Models\CountryCurrencies;
use App\Models\CountryCurrency;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use function Laravel\Prompts\select;

class CountryCurrencyResource extends Resource
{
    protected static ?string $model = CountryCurrencies::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'CountryCurrencies';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
             Select::make('country_id')
            ->label('Country')
            ->relationship('country', 'name')
            ->searchable()
            ->required(),

        Select::make('currency_id')
            ->label('Currency')
            ->relationship('currency', 'name')
            ->searchable()
            ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('country.name')->label('Country'),
            TextColumn::make('currency.name')->label('Currency'),
        ])
        ->recordActions([
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCountryCurrencies::route('/'),
            'create' => CreateCountryCurrency::route('/create'),
            'edit' => EditCountryCurrency::route('/{record}/edit'),
        ];
    }
}
