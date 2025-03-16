<?php

namespace App\Filament\Doctor\Resources\OrderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Doctor\Resources\OrderResource;
use App\Filament\Doctor\Resources\OrderResource\Traits\OrderValidation;

class CreateOrder extends CreateRecord
{
    use OrderValidation;
    
    protected static string $resource = OrderResource::class;

    protected function beforeCreate(): void
    {
        $this->validateOrderProducts();
    }
}