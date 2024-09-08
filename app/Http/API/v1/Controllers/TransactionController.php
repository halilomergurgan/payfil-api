<?php

namespace App\Http\API\v1\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
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
