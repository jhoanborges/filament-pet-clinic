<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \MercadoPago\MercadoPagoConfig config()
 * @method static \MercadoPago\Client\Payment\PaymentClient payment_client()
 * @method static \MercadoPago\Client\Point\PointClient point_client()
 */
class MercadoPago extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mercadopago';
    }
}