<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InventoryTransaction extends Model
{

    public static function boot()
{
    parent::boot();

    static::created(function ($transaction) {
        foreach ($transaction->products as $product) {

            InventoryLog::create([
                'inventory_transaction_id' => $transaction->id,
                'action'                   => $transaction->type, // "entry" or "exit"
                'product_id'               => $product->id,
                'quantity'                 => $product->pivot->quantity,
                'notes'                    => 'Logged automatically on transaction creation.',
            ]);
            
            if ($transaction->type === 'entry') {
                $product->increment('stock', $product->pivot->quantity);
            } else {
                $product->decrement('stock', $product->pivot->quantity);
            }
        }
    });
}

    public function products()
    {
        return $this->hasMany(InventoryTransactionProduct::class);
        //->withPivot('quantity');
    }

    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class);
    }
    
    
    
}
