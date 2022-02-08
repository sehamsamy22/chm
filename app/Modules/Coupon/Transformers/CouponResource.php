<?php

namespace App\Modules\Coupon\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'name' => $request->header('Content-language') ? $this->name : $this->getTranslations('name'),
            'prom_code' => $this->prom_code,
            'amount' => $this->amount,
            'type' => $this->type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'prom_time' => $this->prom_time,
            'used_count' => (int)$this->used_count,
            'max_limit' => $this->max_limit,
        ];
    }
}
