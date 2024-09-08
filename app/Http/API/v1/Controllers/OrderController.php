<?php

namespace App\Http\API\v1\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * @param int $orderId
     * @return JsonResponse
     */
    public function getOrderStatus(int $orderId): JsonResponse
    {
        $order = Order::with('paymentLogs')->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found.'], 404);
        }

        return response()->json(new OrderResource($order), 200);
    }
}
