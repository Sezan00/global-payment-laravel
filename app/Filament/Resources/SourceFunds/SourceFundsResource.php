<?php

namespace App\Filament\Resources\SourceFunds;

use App\Filament\Resources\SourceFunds\Pages\CreateSourceFunds;
use App\Filament\Resources\SourceFunds\Pages\EditSourceFunds;
use App\Filament\Resources\SourceFunds\Pages\ListSourceFunds;
use App\Filament\Resources\SourceFunds\Schemas\SourceFundsForm;
use App\Filament\Resources\SourceFunds\Tables\SourceFundsTable;
use App\Models\SourceFunds;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SourceFundsResource extends Resource
{
    protected static ?string $model = SourceFunds::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'SourceFund';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('source_fund')
             ->label('Source Fund')
             ->maxLength(30),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('source_fund')
                  ->label('source_fund')
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
            'index' => ListSourceFunds::route('/'),
            'create' => CreateSourceFunds::route('/create'),
            'edit' => EditSourceFunds::route('/{record}/edit'),
        ];
    }
}
