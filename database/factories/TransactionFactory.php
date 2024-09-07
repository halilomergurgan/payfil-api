<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'card_number' => '**** **** **** ' . $this->faker->randomNumber(4),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'currency' => $this->faker->randomElement(['USD', 'EUR', 'TRY']),
            'provider' => 'App\\Services\\Payment\\Providers\\Bank' . $this->faker->numberBetween(1, 3),
            'status' => $this->faker->randomElement(['pending', 'success', 'failed']),
            'transaction_id' => $this->faker->uuid(),
            'response_code' => $this->faker->randomElement([200, 404, 500]),
            'user_id' => User::factory(),
            'order_id' => Order::factory(),
        ];
    }
}
