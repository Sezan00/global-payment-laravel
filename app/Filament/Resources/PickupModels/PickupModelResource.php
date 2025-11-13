<?php

namespace App\Filament\Resources\PickupModels;

use App\Filament\Resources\PickupModels\Pages\CreatePickupModel;
use App\Filament\Resources\PickupModels\Pages\EditPickupModel;
use App\Filament\Resources\PickupModels\Pages\ListPickupModels;
use App\Filament\Resources\PickupModels\Schemas\PickupModelForm;
use App\Filament\Resources\PickupModels\Tables\PickupModelsTable;
use App\Models\PickupModel;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PickupModelResource extends Resource
{
    protected static ?string $model = PickupModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'PickupModel';

    public static function form(Schema $schema): Schema
    {
        return $schema
          ->components([
            TextInput::make('name')
             ->required()
             ->maxLength(30),

            TextInput::make('type')
             ->required()
             ->maxLength(30),

            Toggle::make('is_active')
            ->label('Active')
             ->default(true),
          ]);
    }

    public static function table(Table $table): Table
    {
        return $table
         ->columns([
            TextColumn::make('name')
              ->label('name'),
            TextColumn::make('type')
              ->label('type'),
            IconColumn::make('is_active')
             ->label('Active')
              ->boolean(),
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
            'index' => ListPickupModels::route('/'),
            'create' => CreatePickupModel::route('/create'),
            'edit' => EditPickupModel::route('/{record}/edit'),
        ];
    }
}
