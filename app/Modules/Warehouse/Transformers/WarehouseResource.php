<?php

namespace App\Modules\Warehouse\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
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
            'name' => $this->getTranslations('name'),
            'country' => $this->getTranslations('country'),
            'city' => $this->getTranslations('city'),
            'area' => $this->getTranslations('area'),
            'address' => $this->getTranslations('address'),
            'phone' => $this->phone,
            'manager' => $this->manager,
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
        ];
    }
}
