<?php

namespace App\Modules\Address\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'shipping_price' => $this->shipping_price,
            'country' => $request->header('Content-language') ? $this->country->name : $this->country->getTranslations('name'),
            'areas'=> $this->areas
        ];
    }
}
