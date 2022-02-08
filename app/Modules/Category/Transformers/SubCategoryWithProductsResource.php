<?php

namespace App\Modules\Category\Transformers;

use App\Modules\Product\Transformers\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryWithProductsResource extends JsonResource
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
           "products" => ProductResource::collection($this->products),
            "subcategories" => $this->subcategories->map(function ($subcategory) use ($request) {

                return [
                    "id" => $subcategory->id,
                    "name" => $request->header('Content-language') ? $subcategory->name : $subcategory->getTranslations('name')??'',
                    "image" => $subcategory->image,
                    "options" => CategoryOptionResource::collection($subcategory->categoryOptions),
                ];
            }),
        ];
    }
}
