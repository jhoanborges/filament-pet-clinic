<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $appends = ['stock', 'image_url'];
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
        'clinic_id',
        'sku'
    ];
    
    public function inventory()
    {
        return $this->hasOne(Inventory::class);  // A product has one inventory record
    }
    public function clinics(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }
    
    public function clinic(): BelongsTo
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
    
    /**
     * Get the full URL for the product image.
     *
     * @return string|null
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }
        
        // If the image is already a full URL, ensure it uses the correct domain
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            // If the URL is already using the correct domain, return as is
            if (str_contains($this->image, 'pet-clinic.hexagun.mx')) {
                return $this->image;
            }
            
            // Otherwise, convert any incorrect URLs to use the correct domain
            $path = parse_url($this->image, PHP_URL_PATH);
            return 'https://pet-clinic.hexagun.mx' . $path;
        }
        
        // If it's a relative path, construct the full URL
        return 'https://pet-clinic.hexagun.mx/' . ltrim($this->image, '/');
    }
    
    

    public function orders()
{
    return $this->belongsToMany(Order::class);
}

}
