<?php

namespace App\Filament\Resources\PurposeTransfers\Pages;

use App\Filament\Resources\PurposeTransfers\PurposeTransferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPurposeTransfer extends EditRecord
{
    protected static string $resource = PurposeTransferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
