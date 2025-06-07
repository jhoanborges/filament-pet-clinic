<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MercadoPagoTransaction extends Model
{
    protected $table = 'mercado_pago_transactions';

    protected $fillable = [
        'order_id',
        'type',
        'user_id',
        'external_reference',
        'description',
        'processing_mode',
        'country_code',
        'integration_data',
        'status',
        'status_detail',
        'config',
        'transactions',
        'taxes',
        'amount',
        'payment_id',
        'payment_status',
    ];

    protected $casts = [
        'integration_data' => 'array',
        'config' => 'array',
        'transactions' => 'array',
        'taxes' => 'array',
    ];
}
