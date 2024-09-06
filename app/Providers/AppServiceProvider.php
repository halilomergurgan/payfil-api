<?php

namespace App\Providers;

use App\Services\Order\Interfaces\OrderServiceInterface;
use App\Services\Order\OrderService;
use App\Services\Payment\Interfaces\BankPaymentProviderInterface;
use App\Services\Payment\Providers\Bank1PaymentProvider;
use App\Services\Payment\Providers\Bank2PaymentProvider;
use App\Services\Payment\Providers\Bank3PaymentProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BankPaymentProviderInterface::class, Bank1PaymentProvider::class);
        $this->app->bind(BankPaymentProviderInterface::class, Bank2PaymentProvider::class);
        $this->app->bind(BankPaymentProviderInterface::class, Bank3PaymentProvider::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
