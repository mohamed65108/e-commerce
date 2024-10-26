<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOrderNotification implements ShouldQueue
{
    public function handle(OrderPlaced $event)
    {
        // This is where email notification logic would go
        \Log::info('Order placed: ' . $event->order->id);
    }
}

