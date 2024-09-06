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
    public function processPayment(ProcessPaymentRequest $request)
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

            return response()->json(['message' => 'Payment processing started.']);
        } catch (ValidateCardException|PaymentException $e) {
            return $e->render($request);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * @param Transaction $transaction
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function transaction(Transaction $transaction): JsonResponse
    {
        $this->authorize('view', $transaction);

        $transaction->load('order.products');

        return response()->json(new TransactionResource($transaction));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function transactions(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Transaction::class);

        $transactions = Transaction::where('user_id', $request->user()->id)->with('order.products')->get();

        return response()->json(TransactionResource::collection($transactions));
    }
}
