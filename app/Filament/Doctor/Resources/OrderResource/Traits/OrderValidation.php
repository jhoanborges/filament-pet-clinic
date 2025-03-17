<?php

namespace App\Filament\Doctor\Resources\OrderResource\Traits;

use Filament\Notifications\Notification;
use App\Models\Product;

trait OrderValidation
{
    protected function validateOrderProducts(): void
    {
        if (isset($this->data['orderProducts'])) {
            foreach ($this->data['orderProducts'] as $record) {
                $product = Product::where('id', $record['product_id'])->first();

                if (floatval($record['price']) <= 0) {
                    Notification::make()
                        ->warning()
                        ->title('Price error!')
                        ->body('The selected product can not cost zero')
                        ->persistent()
                        ->send();
                    $this->halt();
                }

                if ($product->stock < $record['quantity']) {
                    Notification::make()
                        ->warning()
                        ->title('Insufficient stock!')
                        ->body('The selected product does not have enough stock.')
                        ->persistent()
                        ->send();
                    $this->halt();
                }
            }
        } else {
            Notification::make()
                ->error()
                ->title('No products')
                ->body('Please add products to continue.')
                ->persistent()
                ->send();
            $this->halt();
        }
    }
}