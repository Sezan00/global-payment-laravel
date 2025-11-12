<?php

namespace App\Filament\Resources\PurposeTransfers;

use App\Filament\Resources\PurposeTransfers\Pages\CreatePurposeTransfer;
use App\Filament\Resources\PurposeTransfers\Pages\EditPurposeTransfer;
use App\Filament\Resources\PurposeTransfers\Pages\ListPurposeTransfers;
use App\Filament\Resources\PurposeTransfers\Schemas\PurposeTransferForm;
use App\Filament\Resources\PurposeTransfers\Tables\PurposeTransfersTable;
use App\Models\PurposeTransfer;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurposeTransferResource extends Resource
{
    protected static ?string $model = PurposeTransfer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'PurposeTransfers';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('purpose_transfer')
             ->label('Purpose Transfer')
             ->maxLength(30),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
          ->columns([
            TextColumn::make('purpose_transfer')
             ->label('purpose_transfer')
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
            'index' => ListPurposeTransfers::route('/'),
            'create' => CreatePurposeTransfer::route('/create'),
            'edit' => EditPurposeTransfer::route('/{record}/edit'),
        ];
    }
}
