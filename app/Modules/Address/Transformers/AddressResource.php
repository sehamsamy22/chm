<?php

namespace App\Modules\Address\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'user' => $this->user->name,
            'area' => new AreaResource($this->area),
            // 'shipping_price' => $this->area->shipping_price,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'default' => $this->default,
        ];
    }
}
