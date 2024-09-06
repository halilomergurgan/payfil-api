<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order' => $this->order ? [
                'id' => $this->order->id,
                'status' => $this->order->status,
                'total_amount' => $this->order->total_amount,
                'created_at' => $this->order->created_at->toDateTimeString(),
                'updated_at' => $this->order->updated_at->toDateTimeString(),
                'products' => $this->order->products->map(function($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $product->pivot->quantity,
                    ];
                })
            ] : null,
            'full_name' => $this->full_name,
            'card_number' => $this->card_number,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'provider' => $this->provider,
            'transaction_id' => $this->transaction_id,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
