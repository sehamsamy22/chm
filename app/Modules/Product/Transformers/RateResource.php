<?php

namespace App\Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
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
            'user' => $this->name,
            'product_id' => $this->pivot->product_id,
            'rate_avg' => $this->pivot->rate_avg,
            'product_negatives' => $this->pivot->product_negatives,
            'product_positives' => $this->pivot->product_positives,

        ];
    }
}
