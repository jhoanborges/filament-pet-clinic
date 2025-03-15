<?php

namespace App\Observers;

use App\Models\InventoryLog;
use App\Models\InventoryTransaction;

class InventoryTransactionObserver
{
    /**
     * Handle the InventoryTransaction "created" event.
     */
    public function created(InventoryTransaction $transaction): void
    {
        // Loop through related products from the pivot table
        \Log::info('Observer triggered for transaction ID: ' . json_encode($transaction->toArray()));
        foreach ($transaction->products as $product) {
            InventoryLog::create([
                'inventory_transaction_id' => $transaction->id,
                'action'                   => $transaction->type, // "entry" or "exit"
                'product_id'               => $product->id,
                'quantity'                 => $product->pivot->quantity,
                'notes'                    => 'Logged automatically on transaction creation.',
            ]);
        }
    }

    /**
     * Handle the InventoryTransaction "updated" event.
     */
    public function updated(InventoryTransaction $inventoryTransaction): void
    {
        //
    }

    /**
     * Handle the InventoryTransaction "deleted" event.
     */
    public function deleted(InventoryTransaction $inventoryTransaction): void
    {
        //
    }

    /**
     * Handle the InventoryTransaction "restored" event.
     */
    public function restored(InventoryTransaction $inventoryTransaction): void
    {
        //
    }

    /**
     * Handle the InventoryTransaction "force deleted" event.
     */
    public function forceDeleted(InventoryTransaction $inventoryTransaction): void
    {
        //
    }
}
