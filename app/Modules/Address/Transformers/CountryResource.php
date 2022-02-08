<?php

namespace App\Modules\Address\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'currency' => $request->header('Content-language') ? optional($this->currency)->name : optional($this->currency)->getTranslations('name'),
             'code' =>  optional($this->currency)->code,
            'flag' => $this->flag,
            'stores' => $this->stores->transform(function ($store) use ($request) {
                return [
                    'id' => $store->id,
                    'name' => $request->header('Content-language') ? $store->name : $store->getTranslations('name'),
                    'logo' => $store->logo,
                ];
            })

        ];
    }
}
