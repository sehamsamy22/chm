<?php

namespace App\Modules\Category\Transformers;

use App\Modules\Category\Entities\Option;
use App\Modules\Product\Transformers\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryDashboardResource extends JsonResource
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

            "categories" => CategoryResource::collection($this),
            "options" => OptionResource::collection(Option::all()),

        ];
    }
}
