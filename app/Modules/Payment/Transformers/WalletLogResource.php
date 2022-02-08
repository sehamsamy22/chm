<?php

namespace App\Modules\Payment\Transformers;

use App\Http\Resources\Customer\UserResource;
use App\Modules\Order\Transformers\OrderResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'status' => $this->status,
            'created_At' => $this->created_at->format('d-m-Y'),
            'order' => new OrderResource($this->order),
            'user' => new UserResource($this->wallet->user),
        ];
    }
}
