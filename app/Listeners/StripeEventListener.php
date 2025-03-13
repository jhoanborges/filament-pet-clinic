<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Cashier\Events\WebhookReceived;
use Laravel\Cashier\Cashier;
class StripeEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        
        if ($event->payload['type'] === 'invoice.payment_succeeded') {
            \Log::info(json_encode($event->payload));
            // Handle the incoming event...
        }
    }
}
