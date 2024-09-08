<?php

namespace App\Pipelines;

use App\Services\Order\Interfaces\OrderServiceInterface;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderPipeline
{
    protected OrderServiceInterface $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @param $paymentData
     * @param Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($paymentData, Closure $next)
    {
        if (empty($paymentData['products'])) {
            throw new \Exception('Products data is missing.');
        }

        DB::beginTransaction();

        try {
            $order = $this->orderService->createOrder([
                'user_id' => auth()->id(),
                'products' => $paymentData['products']
            ]);

            $paymentData['order_id'] = $order->id;
            $paymentData['uuid'] = $order->uuid;

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Order Service Fail');

            throw new \Exception('Order Service Fail');
        }

        return $next($paymentData);
    }
}
