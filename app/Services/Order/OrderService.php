<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\Product;
use App\Services\Order\Interfaces\OrderServiceInterface;

class OrderService implements OrderServiceInterface
{
    public function createOrder(array $orderDetails): Order
    {
        $totalAmount = 0;

        foreach ($orderDetails['products'] as $productData) {
            $product = Product::findOrFail($productData['product_id']);
            $totalAmount += $product->price * $productData['quantity'];
        }

        $order = Order::create([
            'user_id' => $orderDetails['user_id'],
            'status' => 'pending',
            'total_amount' => $totalAmount
        ]);

        foreach ($orderDetails['products'] as $productData) {
            $order->products()->attach($productData['product_id'], ['quantity' => $productData['quantity']]);
        }

        return $order;
    }
}
