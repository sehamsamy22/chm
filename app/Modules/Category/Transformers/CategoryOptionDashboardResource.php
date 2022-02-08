<?php

namespace App\Modules\Category\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryOptionDashboardResource extends JsonResource
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
            'id' => $this->id,
            'category' => $request->header('Content-language') ? $this->category->name : $this->category->getTranslations('name'),
            'option' => $request->header('Content-language') ? optional($this->option)->name : optional($this->option)->getTranslations('name'),
            'option_id' => $this->option_id,
            'option_type' => optional($this->option)->input_type,
//            'values' => $this->option->values->map(function ($value)  {
//                return [
//                    "id" => $value->id ,
//                    "value" => $value->value ??$value->color->color,
//                    'color_id' => $value->color_id
//                ];
//            })
        ];
    }
}
