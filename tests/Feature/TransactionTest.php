<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Tests\BaseTest;

class TransactionTest extends BaseTest
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_transaction_list()
    {
        $order = Order::factory()->create();
        Transaction::factory()->create(['order_id' => $order->id]);

        $response = $this->apiGet('/transactions');
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id', 'user_id', 'order', 'full_name', 'card_number', 'amount', 'currency', 'status', 'transaction_id'
                ]
            ]);
    }

    public function test_view_single_transaction()
    {
        $order = Order::factory()->create();
        $transaction = Transaction::factory()->create(['order_id' => $order->id]);

        $response = $this->apiGet("/transaction/{$transaction->id}");
        $response->assertStatus(200)
            ->assertJson([
                'id' => $transaction->id,
                'amount' => $transaction->amount,
            ]);
    }
}
