<?php

namespace App\Modules\Category\Transformers;

use App\Modules\Product\Transformers\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {
        dd($this->packageItems());
        return [
            'id' => $this->id,
            'name' => $request->header('Content-language') ? $this->name : $this->getTranslations('name') ?? '',
            'image' => $this->image,
            'have_additions' => $this->have_additions,
            'parentCategory' => $this->mainCategory,
            'is_package' => $this->is_package,
            "subcategories" => $this->subcategories->map(function ($subcategory) use ($request) {
                return [
                    "id" => $subcategory->id,
                    "name" => $request->header('content-language') ? $subcategory->name : $subcategory->getTranslations('name') ?? '',
                    "image" => $subcategory->image,
                    "options" => CategoryOptionResource::collection($subcategory->categoryOptions),
                ];
            }),
            "options" => CategoryOptionResource::collection($this->categoryOptions),
            "products" => ProductResource::collection($this->packageItems()),
        ];
    }
}
