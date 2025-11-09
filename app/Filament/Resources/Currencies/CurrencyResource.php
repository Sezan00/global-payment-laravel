<?php

namespace App\Filament\Resources\Currencies;

use App\Filament\Resources\Currencies\Pages\CreateCurrency;
use App\Filament\Resources\Currencies\Pages\EditCurrency;
use App\Filament\Resources\Currencies\Pages\ListCurrencies;
use App\Filament\Resources\Currencies\Schemas\CurrencyForm;
use App\Filament\Resources\Currencies\Tables\CurrenciesTable;
use App\Models\Currency;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'currencies';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
             TextInput::make('name')
            ->label('Currency Name')
            ->required()
            ->maxLength(50),

        TextInput::make('code')
            ->label('Currency Code')
            ->required()
            ->maxLength(10),

        TextInput::make('symbol')
            ->label('Symbol')
            ->maxLength(5),

        TextInput::make('decimal_places')
            ->numeric()
            ->label('Decimal Places')
            ->default(2),
          
        ]);
    }

  public static function table(Table $table): Table
{
   return $table
    ->columns([
        TextColumn::make('name')
            ->label('Currency Name'),

        TextColumn::make('code')
            ->label('Currency Code'),

        TextColumn::make('symbol')
            ->label('Symbol'),

        TextColumn::make('decimal_places')
            ->label('Decimal Places'),

        TextColumn::make('currencies.name')
           ->label('Currencies')
           ->limitList(3),
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
            'index' => ListCurrencies::route('/'),
            'create' => CreateCurrency::route('/create'),
            'edit' => EditCurrency::route('/{record}/edit'),
        ];
    }
}
