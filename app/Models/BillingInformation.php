<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingInformation extends Model
{
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
