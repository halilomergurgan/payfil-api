<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\PaymentLog;
use Tests\BaseTest;

class OrderTest extends BaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_order_status_and_logs_are_returned_correctly()
    {
        $order = Order::factory()->create();
        $paymentLog = PaymentLog::create([
            'order_id' => $order->id,
            'status' => 'processing',
            'logged_at' => now(),
        ]);

        $response = $this->apiGet("/order-status/{$order->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'order_id',
                'user_id',
                'status',
                'total_amount',
                'created_at',
                'logs' => [
                    '*' => [
                        'status',
                        'logged_at',
                    ]
                ]
            ])
            ->assertJsonFragment([
                'status' => 'processing',
                'logged_at' => $paymentLog->logged_at->toDateTimeString(),
            ]);
    }
}
