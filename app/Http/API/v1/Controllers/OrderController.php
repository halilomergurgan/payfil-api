<?php

namespace App\Http\API\v1\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * @param string $uuid
     * @return JsonResponse
     */
    public function getOrderStatus(string $uuid): JsonResponse
    {
        $order = Order::with('paymentLogs')->where(['uuid' => $uuid])->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found.'], 404);
        }

        return response()->json(new OrderResource($order), 200);
    }
}
