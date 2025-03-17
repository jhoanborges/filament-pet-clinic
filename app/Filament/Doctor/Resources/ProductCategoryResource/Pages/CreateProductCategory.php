<?php

namespace App\Filament\Doctor\Resources\ProductCategoryResource\Pages;

use App\Filament\Doctor\Resources\ProductCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductCategory extends CreateRecord
{
    protected static string $resource = ProductCategoryResource::class;
}
