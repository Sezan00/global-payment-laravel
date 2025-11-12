<?php

namespace App\Filament\Resources\Relations;

use App\Filament\Resources\Relations\Pages\CreateRelation;
use App\Filament\Resources\Relations\Pages\EditRelation;
use App\Filament\Resources\Relations\Pages\ListRelations;
use App\Filament\Resources\Relations\Schemas\RelationForm;
use App\Filament\Resources\Relations\Tables\RelationsTable;
use App\Models\Relation;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RelationResource extends Resource
{
    protected static ?string $model = Relation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Relation';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('relation')
               ->label('Relation')
               ->maxLength(30),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
          ->columns([
            TextColumn::make('relation')
             ->label('relation'),
          ])
          ->recordActions([
            DeleteAction::make(),
            EditAction::make(),
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
            'index' => ListRelations::route('/'),
            'create' => CreateRelation::route('/create'),
            'edit' => EditRelation::route('/{record}/edit'),
        ];
    }
}
