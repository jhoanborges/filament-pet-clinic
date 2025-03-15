<?php

namespace App\Filament\Doctor\Resources\InventoryLogResource\Pages;

use App\Filament\Doctor\Resources\InventoryLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryLogs extends ListRecords
{
    protected static string $resource = InventoryLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
