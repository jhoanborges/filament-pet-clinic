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
