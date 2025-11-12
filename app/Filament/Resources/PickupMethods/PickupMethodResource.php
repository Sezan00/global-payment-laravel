<?php

namespace App\Filament\Resources\PickupMethods;

use App\Filament\Resources\PickupMethods\Pages\CreatePickupMethod;
use App\Filament\Resources\PickupMethods\Pages\EditPickupMethod;
use App\Filament\Resources\PickupMethods\Pages\ListPickupMethods;
use App\Filament\Resources\PickupMethods\Schemas\PickupMethodForm;
use App\Filament\Resources\PickupMethods\Tables\PickupMethodsTable;
use App\Models\PickupMethod;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PickupMethodResource extends Resource
{
    protected static ?string $model = PickupMethod::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'PickupMethod';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('method')
                ->label('Method')
                ->maxLength(30)
        ]);
    }

    public static function table(Table $table): Table
{
   return $table
    ->columns([
        TextColumn::make('method')
            ->label('method'),

        TextColumn::make('details')
            ->label('details'),
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
            'index' => ListPickupMethods::route('/'),
            'create' => CreatePickupMethod::route('/create'),
            'edit' => EditPickupMethod::route('/{record}/edit'),
        ];
    }
}
