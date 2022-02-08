<?php

namespace App\Modules\Store\Transformers;

use App\Modules\Address\Transformers\CountryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
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
            'description' =>  $request->header('Content-language') ? $this->description : $this->getTranslations('description'),
            'logo' => $this->logo,
            'country' => new CountryResource($this->country),
        ];
    }
}
