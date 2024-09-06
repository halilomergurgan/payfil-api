<?php

namespace App\Services\Payment;

use App\Services\Payment\Interfaces\BankPaymentProviderInterface;

class PaymentService
{
    protected BankPaymentProviderInterface $provider;

    /**
     * @param BankPaymentProviderInterface $provider
     */
    public function __construct(BankPaymentProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param array $cardDetails
     * @return bool
     */
    public function validateCard(array $cardDetails): bool
    {
        return $this->provider->validateCard($cardDetails);
    }

    /**
     * @param float $amount
     * @param string $currency
     * @return bool
     */
    public function processPayment(float $amount, string $currency): bool
    {
        return $this->provider->processPayment($amount, $currency);
    }

    /**
     * @param string $transactionId
     * @return array
     */
    public function getTransactionDetails(string $transactionId): array
    {
        return $this->provider->getTransactionDetails($transactionId);
    }
}
