<?php

namespace App\Filament\Resources\SourceFunds\Pages;

use App\Filament\Resources\SourceFunds\SourceFundsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSourceFunds extends EditRecord
{
    protected static string $resource = SourceFundsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
