<?php

namespace App\Jobs;

use App\Services\Payment\PaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;
use App\Exceptions\PaymentException;

class ProcessPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $paymentData;
    protected $provider;

    public function __construct($paymentData, $provider)
    {
        $this->paymentData = $paymentData;
        $this->provider = $provider;
    }

    /**
     * @throws PaymentException
     */
    public function handle(PaymentService $paymentService)
    {
        $maskedCardNumber = substr($this->paymentData['cardNumber'], -4);
        $maskedCardNumber = '**** **** **** ' . $maskedCardNumber;

        try {
            if (!$paymentService->processPayment($this->paymentData['amount'], $this->paymentData['currency'])) {
                throw new PaymentException('Payment failed due to invalid amount or currency.');
            }

            Transaction::create([
                'full_name' => $this->paymentData['fullName'],
                'card_number' => $maskedCardNumber,
                'amount' => $this->paymentData['amount'],
                'currency' => $this->paymentData['currency'],
                'provider' => get_class($this->provider),
                'status' => 'success',
                'transaction_id' => uniqid(),
                'response_code' => 200
            ]);

        } catch (PaymentException $e) {
            Transaction::create([
                'full_name' => $this->paymentData['fullName'],
                'card_number' => $maskedCardNumber,
                'amount' => $this->paymentData['amount'],
                'currency' => $this->paymentData['currency'],
                'provider' => get_class($this->provider),
                'status' => 'failed',
                'transaction_id' => uniqid(),
                'response_code' => 404
            ]);

            throw $e;
        }
    }
}
