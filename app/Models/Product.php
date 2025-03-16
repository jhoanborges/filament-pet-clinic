<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $appends = ['stock'];
    
    public function inventory()
    {
        return $this->hasOne(Inventory::class);  // A product has one inventory record
    }
    public function clinics(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }
    
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function inventoryTransactions(): BelongsToMany
    {
        return $this->belongsToMany(InventoryTransaction::class, 'inventory_transaction_product')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function inventoryTransactionProducts()
    {
        return $this->hasMany(InventoryTransactionProduct::class, 'product_id');
    }

        /**
     * Accesor para obtener el stock total.
     * Suma las cantidades de las entradas y resta las de las salidas.
     */
    public function getStockAttribute()
    {
        // Se asume que la relación 'inventoryTransaction' en InventoryTransactionProduct ya está definida.
        return $this->inventoryTransactionProducts->sum(function ($item) {
            $type = $item->inventoryTransaction->type;
            return $type === 'entry' ? $item->quantity : -$item->quantity;
        });
    }
    
    

    public function orders()
{
    return $this->belongsToMany(Order::class);
}

}
