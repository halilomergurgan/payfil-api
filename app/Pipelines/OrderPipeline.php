<?php

namespace App\Pipelines;

use App\Services\Order\Interfaces\OrderServiceInterface;
use Closure;

class OrderPipeline
{
    protected OrderServiceInterface $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    public function handle($paymentData, Closure $next)
    {
        if (empty($paymentData['products'])) {
            throw new \Exception('Products data is missing.');
        }

        $order = $this->orderService->createOrder([
            'user_id' => auth()->id(),
            'products' => $paymentData['products']
        ]);

        $paymentData['order_id'] = $order->id;

        return $next($paymentData);
    }
}
