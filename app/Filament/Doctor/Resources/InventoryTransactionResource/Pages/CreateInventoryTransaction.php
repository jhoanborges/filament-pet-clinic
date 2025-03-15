<?php

namespace App\Filament\Doctor\Resources\InventoryTransactionResource\Pages;

use App\Filament\Doctor\Resources\InventoryTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInventoryTransaction extends CreateRecord
{
    protected static string $resource = InventoryTransactionResource::class;

    protected function afterSave(): void
    {
        dd($this->record);
        // Runs after the form fields are saved to the database.
    }


}
