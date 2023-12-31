<?php

namespace App\Modules\Category\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Category\Entities\Category;
use App\Modules\Category\Transformers\CategoryDetailResource;
use App\Modules\Category\Transformers\CategoryWithAdditionsResource;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Transformers\ProductResource;
use App\Scopes\NormalProductScope;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $categories = isset($request['type']) ? Category::haveAdditions()->get() : Category::notHaveAdditions()->get();
        return $this->apiResponse(CategoryWithAdditionsResource::collection($categories));
    }

    public function show(Category $category)
    {
        return $this->apiResponse(new CategoryDetailResource($category));
    }

    public function categoryAdditions($id)
    {
        $additions = Product::withoutGlobalScope(NormalProductScope::class)->where('category_id', $id)->get();
        return $this->apiResponse(ProductResource::collection($additions));
    }
}
