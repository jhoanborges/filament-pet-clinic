<?php

namespace App\Providers;

use App\Models\Clinic;
use App\Models\User;
use Laravel\Cashier\Cashier;
use App\Models\Cashier\Subscription;
use App\Models\InventoryTransaction;
use Laravel\Cashier\SubscriptionItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Observers\InventoryTransactionObserver;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

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

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
            ->displayLocale('es')
                ->locales([
                    'es',
                    'en',
                ])
                ->flags([
                    'es' => asset('images/es.svg'),
                    'en' => asset('images/us.svg'),
                ]); // also accepts a closure
        });

        InventoryTransaction::observe(InventoryTransactionObserver::class);
        Model::unguard(true);

        Cashier::useCustomerModel(User::class);
        Cashier::useSubscriptionModel(Subscription::class);
        //Cashier::useSubscriptionItemModel(SubscriptionItem::class);
        //Cashier::calculateTaxes();
    }
}
