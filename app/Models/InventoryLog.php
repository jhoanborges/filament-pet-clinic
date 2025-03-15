<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InventoryLog extends Model
{
    
    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class);
    }
    
}
