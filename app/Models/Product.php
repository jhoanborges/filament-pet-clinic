<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function inventory()
    {
        return $this->hasOne(Inventory::class);  // A product has one inventory record
    }
    public function clinics(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }
    
}
