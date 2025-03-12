<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Client extends Model
{
    use HasFactory;
    protected $casts = [
        'birthday' => 'date',
        'allow_email_notification' => 'boolean',
    ];
    
    public function billingInformation()
    {
        return $this->hasOne(BillingInformation::class);
    }

    public function pet()
    {
        return $this->hasOne(Pet::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }




}
