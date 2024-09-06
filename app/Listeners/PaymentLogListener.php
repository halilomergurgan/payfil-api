<?php

namespace App\Listeners;

use App\Events\PaymentCompleted;
use App\Events\PaymentFailed;
use App\Events\PaymentProcessing;
use App\Events\PaymentStarted;
use App\Models\Order;
use App\Models\PaymentLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PaymentLogListener
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
    public function handle(object $event): void
    {
        PaymentLog::create([
            'order_id' => $event->orderId,
            'status' => $this->getStatus($event),
            'logged_at' => now()
        ]);
    }

    /**
     * @param $event
     * @return string
     */
    protected function getStatus($event): string
    {
        if ($event instanceof PaymentStarted) {
            return Order::STATUS_PENDING;
        } elseif ($event instanceof PaymentProcessing) {
            return Order::STATUS_PROCESSING;
        } elseif ($event instanceof PaymentCompleted) {
            return Order::STATUS_COMPLETED;
        } elseif ($event instanceof PaymentFailed) {
            return Order::STATUS_FAILED;
        }
        return 'unknown';
    }
}
