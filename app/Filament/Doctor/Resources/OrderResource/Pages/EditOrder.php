<?php

namespace App\Filament\Doctor\Resources\OrderResource\Pages;

use App\Filament\Doctor\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Doctor\Resources\OrderResource\Traits\OrderValidation;

class EditOrder extends EditRecord
{
    use OrderValidation;
    
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function beforeSave(): void
    {
        $this->validateOrderProducts();
    }
}