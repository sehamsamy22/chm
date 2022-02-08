<?php

namespace App\Modules\Product\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->getTranslations('name'),
            "discount" => $this->discount,
            "paid_quantity" => $this->paid_quantity,
            "discounted_quantity" => $this->discounted_quantity,
            "start_date" => $this->start_date,
            "expiration_date" => $this->expiration_date,
            "list_id" => new ListResource($this->list),
        ];
    }
}
