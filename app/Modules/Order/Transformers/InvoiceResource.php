<?php

namespace App\Modules\Order\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'total' => $this->total,
            'order_id' => $this->order_id,
            'coupon_id' => $this->coupon_id,
            'log' => $this->logs->map(function ($log) {
                return [
                    "fees_name" => __("order::invoice.{$log->fees_name}"),
                    "fees_type" => $log->fees_type,
                    "cost" => $log->cost
                ];
            })
        ];
    }
}
