<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryTransactionProduct extends Model
{
    use HasFactory;

    protected $table = 'inventory_transaction_product';

    protected $fillable = [
        'inventory_transaction_id',
        'product_id',
        'quantity',
    ];


    public function inventoryTransaction()
    {
        return $this->belongsTo(InventoryTransaction::class);
    }
}
