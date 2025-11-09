<?php

namespace App\Filament\Resources\Countryes;

use App\Filament\Resources\Countryes\Pages\CreateCountrye;
use App\Filament\Resources\Countryes\Pages\EditCountrye;
use App\Filament\Resources\Countryes\Pages\ListCountryes;
use App\Filament\Resources\Countryes\Schemas\CountryeForm;
use App\Filament\Resources\Countryes\Tables\CountryesTable;
use App\Models\Country;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CountryeResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name'),
            TextInput::make('iso2'),
            TextInput::make('iso3'),
            FileUpload::make('flag_url'),

            \Filament\Forms\Components\Select::make('currencies')
            ->label('Currencies')
            ->multiple() 
            ->relationship('currencies', 'name') 
            ->preload()
            ->searchable()
            ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('flag_url'),
            TextColumn::make('name'),
            TextColumn::make('iso2')
                ->label('ISO 2'),
            TextColumn::make('iso3'),
        ])->recordActions([
            EditAction::make('Edit'),
            DeleteAction::make('Delete'),
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
            'index' => ListCountryes::route('/'),
            'create' => CreateCountrye::route('/create'),
            'edit' => EditCountrye::route('/{record}/edit'),
        ];
    }
}
