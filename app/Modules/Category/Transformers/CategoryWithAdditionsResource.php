<?php

namespace App\Modules\Category\Transformers;

use App\Modules\Product\Transformers\ProductResource;
use App\Scopes\NormalProductScope;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryWithAdditionsResource extends JsonResource
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
            'name' => $request->header('Content-language') ? $this->name : $this->getTranslations('name'),
            'image' => $this->image,
            "subcategories" => $this->subcategories->map(function ($subcategory) use ($request) {
                return [
                    "id" => $subcategory->id,
                    "name" => $request->header('content-language') ? $subcategory->name : $subcategory->getTranslations('name')??'',
                    "image" => $subcategory->image,
                    "options" => CategoryOptionResource::collection($subcategory->categoryOptions),
                ];
            }),
            "options" => CategoryOptionResource::collection($this->categoryOptions),
            "products" => ProductResource::collection($this->additions()),

        ];
    }
}
