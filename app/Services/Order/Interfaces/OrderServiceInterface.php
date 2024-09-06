<?php

namespace App\Services\Order\Interfaces;

use App\Models\Order;

interface OrderServiceInterface
{
    /**
     * @param array $orderDetails
     * @return Order
     */
    public function createOrder(array $orderDetails): Order;
}
