<?php

namespace App\Models;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    
    protected $appends = ['order_total'];

    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class);
    }
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot(['quantity', 'price']);
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class);
    }

    /**
     * Get the order's products total.
     */
    public function getOrderTotalAttribute()
    {
        $total =  0;
        foreach ($this->orderProducts as $product) {
            $total = $product->price;
        }
        return $total;
    }
}
