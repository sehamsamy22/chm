<?php

namespace App\Modules\Product\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {
//        dd($this);
        return [
           'id' => $this->id,
            'name' => $request->header('Content-language')? $this->name : $this->getTranslations('name'),
            'input_type' => $this->input_type,
//            'option_type' => optional($this->option)->input_type,
           'values' => $this->values->map(function ($value) use ($request) {
                return [
                    'id' => $value->id,
                    'color_id' => $value->color_id,
                    "value" => $value->value ?? $value->color->color,
                    'color_name' => $request->header('Content-language') ? optional($value->color)->name : optional($value->color)->getTranslations('name'),
                ];
            }) ,
        ];
    }
}
