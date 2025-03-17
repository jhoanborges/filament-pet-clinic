<?php

namespace App\Filament\Doctor\Resources\InventoryTransactionResource\Pages;

use App\Filament\Doctor\Resources\InventoryTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventoryTransaction extends EditRecord
{
    protected static string $resource = InventoryTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
