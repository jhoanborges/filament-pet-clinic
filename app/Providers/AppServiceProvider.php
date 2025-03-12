<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Models\Cashier\User;
use App\Models\Clinic;
use Laravel\Cashier\Cashier;
use App\Models\Cashier\Subscription;
use App\Models\Cashier\SubscriptionItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard(true);
        Cashier::useCustomerModel(Clinic::class);
        Cashier::useSubscriptionModel(Subscription::class);
        //Cashier::useSubscriptionItemModel(SubscriptionItem::class);
        //Cashier::calculateTaxes();
    }
}
