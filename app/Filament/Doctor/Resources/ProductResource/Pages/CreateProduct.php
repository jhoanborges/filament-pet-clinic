<?php

namespace App\Filament\Doctor\Resources\ProductResource\Pages;

use App\Filament\Doctor\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
