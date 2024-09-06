<?php

namespace App\Providers;

use App\Events\PaymentCompleted;
use App\Events\PaymentFailed;
use App\Events\PaymentProcessing;
use App\Events\PaymentStarted;
use App\Listeners\PaymentLogListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        PaymentStarted::class => [
            PaymentLogListener::class,
        ],
        PaymentProcessing::class => [
            PaymentLogListener::class,
        ],
        PaymentCompleted::class => [
            PaymentLogListener::class,
        ],
        PaymentFailed::class => [
            PaymentLogListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
