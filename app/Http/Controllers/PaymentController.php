<?php

namespace App\Http\Controllers;

use App\Exceptions\PaymentException;
use App\Exceptions\ValidateCardException;
use App\Http\Requests\ProcessPaymentRequest;
use App\Pipelines\ProcessPayment;
use App\Pipelines\SelectBank;
use App\Pipelines\ValidateCard;
use Illuminate\Pipeline\Pipeline;
use App\Jobs\ProcessPaymentJob;

class PaymentController extends Controller
{
    public function processPayment(ProcessPaymentRequest $request)
    {
        $paymentData = $request->validated();

        try {
            $request = app(Pipeline::class)
                ->send($paymentData)
                ->through([
                    ValidateCard::class,
                    SelectBank::class,
                    ProcessPayment::class,
                ])
                ->thenReturn();

            ProcessPaymentJob::dispatch($paymentData, $request['provider']);

            return response()->json(['message' => 'Payment processing started.']);
        } catch (ValidateCardException | PaymentException $e) {
            return $e->render($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
