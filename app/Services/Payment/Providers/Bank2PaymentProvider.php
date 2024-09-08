<?php

namespace App\Services\Payment\Providers;

use App\Services\Payment\Interfaces\BankPaymentProviderInterface;
use App\Traits\LuhnTrait;

class Bank2PaymentProvider implements BankPaymentProviderInterface
{
    use LuhnTrait;

    /**
     * @param array $cardDetails
     * @return bool
     */
    public function validateCard(array $cardDetails): bool
    {
        return $this->isValidLuhn($cardDetails['number']);
    }

    /**
     * @param float $amount
     * @param string $currency
     * @return bool
     */
    public function processPayment(float $amount, string $currency): bool
    {
        if ($amount > 0 && in_array($currency, ['USD', 'EUR', 'TRY'])) {
            return true;
        }
        return false;
    }

    /**
     * @param string $transactionId
     * @return array
     */
    public function getTransactionDetails(string $transactionId): array
    {
        return [
            'transactionId' => $transactionId,
            'status' => 'success',
            'amount' => rand(10, 1000),
            'currency' => 'USD',
        ];
    }
}
