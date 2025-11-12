<?php

namespace App\Filament\Resources\Relations\Pages;

use App\Filament\Resources\Relations\RelationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRelation extends EditRecord
{
    protected static string $resource = RelationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
