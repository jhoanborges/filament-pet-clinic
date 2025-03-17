<?php

namespace App\Filament\Doctor\Resources\OrderResource\Pages;

use Filament\Actions;
use App\Models\InventoryLog;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Doctor\Resources\OrderResource;
use App\Filament\Doctor\Resources\OrderResource\Traits\OrderValidation;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionProduct;

class CreateOrder extends CreateRecord
{
    use OrderValidation;

    protected static string $resource = OrderResource::class;

    protected function beforeCreate(): void
    {
        $this->validateOrderProducts();
    }

    protected function afterCreate(): void
    {
        $order = $this->record;

        //cuando se crea una orden de venta, entonces se hace una salida de inventario
        $inventoryTransaction = InventoryTransaction::create([
            'reference' => uniqid(),
            'type' => 'exit'
        ]);

        foreach ($order->orderProducts as $product) {

            InventoryLog::create([
                'inventory_transaction_id' => $inventoryTransaction->id,
                'action'                   => $inventoryTransaction->type, // "entry" or "exit"
                'product_id'               => $product->product_id,
                'quantity'                 => $product->quantity,
                'notes'                    => 'Logged automatically on transaction creation.',
            ]);

            InventoryTransactionProduct::create([
                'inventory_transaction_id'=> $inventoryTransaction->id,
                'product_id' => $product->product_id,
                'quantity' => $product->quantity
            ]);
            
        }
    }
}
