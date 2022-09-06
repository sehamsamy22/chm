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
        $categories = isset($request['type']) ? Category::haveAdditions(): Category::notHaveAdditions();
        $categories=$categories->with(["subcategories.categoryOptions.option.values.color","products.rates","categoryOptions"])->where('is_package',0)->get();
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
