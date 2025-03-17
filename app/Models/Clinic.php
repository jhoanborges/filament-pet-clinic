<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Billable;

class Clinic extends Model
{
    use Billable;
    use HasFactory;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    

    public function pets(): BelongsToMany
    {
        return $this->belongsToMany(Pet::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function activeAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class)->where('status', 'created');
    }

    public function todaysAppointments(): HasMany
    {
        
        return $this->hasMany(Appointment::class)->where('status', 'created')->where('date', Carbon::today()->format('Y-m-d'));
    }


    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function productCategories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class);
    }

    public function inventoryTransactions(): BelongsToMany
    {
        return $this->belongsToMany(InventoryTransaction::class);
    }

    public function inventoryLog(): BelongsToMany
    {
        return $this->belongsToMany(InventoryLog::class);
    }
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }

}
