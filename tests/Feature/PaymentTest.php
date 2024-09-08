<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Tests\BaseTest;

class PaymentTest extends BaseTest
{
    protected array $payload;

    protected function setUp(): void
    {
        parent::setUp();

        $product = Product::factory()->create();

        $this->payload = [
            'fullName' => 'John Doe',
            'cardNumber' => '4111111111111111',
            'expiryDate' => '12/25',
            'cvv' => '123',
            'amount' => 100.50,
            'currency' => 'USD',
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]
            ]
        ];
    }

    public function test_process_payment_success()
    {
        $response = $this->apiPost('/process-payment', $this->payload);
        $response->assertStatus(200)
            ->assertJson(['message' => 'Payment processing started.']);

        $this->assertDatabaseHas('transactions', [
            'amount' => $this->payload['amount'],
            'currency' => $this->payload['currency'],
            'status' => 'success',
        ]);
    }

    public function test_process_payment_invalid_card_number()
    {
        $this->payload['cardNumber'] = '1234567890123456';

        $response = $this->apiPost('/process-payment', $this->payload);

        $response->assertStatus(422)
            ->assertJson(['error' => 'Card validation failed.']);
    }

    public function test_process_payment_missing_fields()
    {
        $this->payload['cardNumber'] = null;
        $this->payload['fullName'] = null;

        $response = $this->apiPost('/process-payment', $this->payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['fullName', 'cardNumber']);
    }

    public function test_full_name_is_required()
    {
        $this->payload['fullName'] = '';

        $response = $this->apiPost('/process-payment', $this->payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['fullName']);
    }

    public function test_card_number_is_required()
    {
        $this->payload['cardNumber'] = '';

        $response = $this->apiPost('/process-payment', $this->payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cardNumber']);
    }

    public function test_expiry_date_is_required()
    {
        $this->payload['expiryDate'] = '';

        $response = $this->apiPost('/process-payment', $this->payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['expiryDate']);
    }

    public function test_cvv_is_required()
    {
        $this->payload['cvv'] = '';

        $response = $this->apiPost('/process-payment', $this->payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cvv']);
    }

    public function test_amount_is_required()
    {
        $this->payload['amount'] = '';

        $response = $this->apiPost('/process-payment', $this->payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['amount']);
    }

    public function test_currency_is_required()
    {
        $this->payload['currency'] = '';

        $response = $this->apiPost('/process-payment', $this->payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['currency']);
    }

    public function test_product_list_is_required()
    {
        $this->payload['products'] = [];

        $response = $this->apiPost('/process-payment', $this->payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['products']);
    }
}
