<?php

namespace App\Http\API\v1\Controllers;

use App\Exceptions\PaymentException;
use App\Exceptions\ValidateCardException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessPaymentRequest;
use App\Http\Resources\TransactionResource;
use App\Jobs\ProcessPaymentJob;
use App\Models\Transaction;
use App\Pipelines\OrderPipeline;
use App\Pipelines\ProcessPayment;
use App\Pipelines\SelectBank;
use App\Pipelines\ValidateCard;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class PaymentController extends Controller
{
    /**
     * @param ProcessPaymentRequest $request
     * @return JsonResponse
     */
    public function processPayment(ProcessPaymentRequest $request): JsonResponse
    {
        $paymentData = $request->validated();

        try {
            $request = app(Pipeline::class)
                ->send($paymentData)
                ->through([
                    OrderPipeline::class,
                    ValidateCard::class,
                    SelectBank::class,
                    ProcessPayment::class,
                ])
                ->thenReturn();

            ProcessPaymentJob::dispatch($paymentData, $request['provider'], auth()->user(), $request['order_id']);

            return response()->json(['message' => 'Payment processing started.', 'order_id' => $request['order_id']]);
        } catch (ValidateCardException|PaymentException $e) {
            return $e->render($request);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
