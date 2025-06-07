<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Point\PointClient;

class MercadoPagoServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('mercadopago', function () {
            // Set your access token
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
            
            return new class {
                public function config()
                {
                    return new class {
                        public function setAccessToken($token)
                        {
                            return MercadoPagoConfig::setAccessToken($token);
                        }
                    };
                }
                
                public function payment_client()
                {
                    return new PaymentClient();
                }
                
                public function point_client()
                {
                    return new PointClient();
                }
            };
        });
    }

    public function boot()
    {
        //
    }
}