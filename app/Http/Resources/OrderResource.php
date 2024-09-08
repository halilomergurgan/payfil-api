<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'total_amount' => $this->total_amount,
            'created_at' => $this->created_at->toDateTimeString(),
            'logs' => $this->paymentLogs->map(function ($log) {
                return [
                    'status' => $log->status,
                    'logged_at' => $log->logged_at->toDateTimeString(),
                ];
            }),
        ];
    }
}
