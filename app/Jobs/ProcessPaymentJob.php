<?php

namespace App\Jobs;

use App\Events\PaymentCompleted;
use App\Events\PaymentFailed;
use App\Events\PaymentProcessing;
use App\Events\PaymentStarted;
use App\Models\User;
use App\Models\Order;
use App\Services\Payment\PaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;
use App\Exceptions\PaymentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $paymentData;
    protected $provider;
    protected $user;
    protected $orderId;

    public function __construct($paymentData, $provider, User $user, $orderId)
    {
        $this->paymentData = $paymentData;
        $this->provider = $provider;
        $this->user = $user;
        $this->orderId = $orderId;
    }

    /**
     * @throws PaymentException
     */
    public function handle(PaymentService $paymentService)
    {
        event(new PaymentStarted($this->orderId));

        $maskedCardNumber = $this->maskedCardNumber($this->paymentData['cardNumber']);

        try {
            event(new PaymentProcessing($this->orderId));

            if (!$paymentService->processPayment($this->paymentData['amount'], $this->paymentData['currency'])) {
                throw new PaymentException('Payment failed due to invalid amount or currency.');
            }

            $order = Order::find($this->orderId);
            $order->status = Order::STATUS_COMPLETED;
            $order->save();

            Transaction::create([
                'order_id' => $this->orderId,
                'full_name' => $this->paymentData['fullName'],
                'card_number' => $maskedCardNumber,
                'user_id' => $this->user->id,
                'amount' => $this->paymentData['amount'],
                'currency' => $this->paymentData['currency'],
                'provider' => get_class($this->provider),
                'status' => 'success',
                'transaction_id' => uniqid(),
                'response_code' => 200
            ]);

            event(new PaymentCompleted($this->orderId));
        } catch (PaymentException $e) {
            event(new PaymentFailed($this->orderId));

            $order = Order::find($this->orderId);
            $order->status = Order::STATUS_FAILED;
            $order->save();

            Transaction::create([
                'order_id' => $this->orderId,
                'full_name' => $this->paymentData['fullName'],
                'card_number' => $maskedCardNumber,
                'user_id' => $this->user->id,
                'amount' => $this->paymentData['amount'],
                'currency' => $this->paymentData['currency'],
                'provider' => get_class($this->provider),
                'status' => 'failed',
                'transaction_id' => uniqid(),
                'response_code' => 404
            ]);

            Log::error('Process Payment Job Error: '. $e->getMessage());

            throw $e;
        }
    }

    /**
     * @param $maskedCardNumber
     * @return string
     */
    private function maskedCardNumber($maskedCardNumber): string
    {
        $maskedCardNumber = substr($maskedCardNumber, -4);
        return '**** **** **** ' . $maskedCardNumber;
    }
}
