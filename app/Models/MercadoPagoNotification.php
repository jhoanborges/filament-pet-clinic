<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercadoPagoNotification extends Model
{
    use HasFactory;

    protected $table = 'mercadopago_notifications';

    protected $fillable = [
        'notification_id',
        'type',
        'live_mode',
        'action',
        'api_version',
        'user_id',
        'resource_id',
        'status',
        'data',
    ];

    protected $casts = [
        'live_mode' => 'boolean',
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getLatestStatus($resourceId)
    {
        return self::where('resource_id', $resourceId)
            ->latest()
            ->value('status');
    }
}