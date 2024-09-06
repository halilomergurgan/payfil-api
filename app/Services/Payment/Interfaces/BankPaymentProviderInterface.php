<?php

namespace App\Services\Payment\Interfaces;

interface BankPaymentProviderInterface
{
    /**
     * @param array $cardDetails
     * @return bool
     */
    public function validateCard(array $cardDetails): bool;

    /**
     * @param float $amount
     * @param string $currency
     * @return bool
     */
    public function processPayment(float $amount, string $currency): bool;

    /**
     * @param string $transactionId
     * @return array
     */
    public function getTransactionDetails(string $transactionId): array;
}
