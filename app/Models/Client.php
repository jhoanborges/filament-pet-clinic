<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

}
