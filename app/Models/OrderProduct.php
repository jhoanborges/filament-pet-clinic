<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderProduct extends Pivot
{
    use HasFactory;
    
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
 
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

  
}
