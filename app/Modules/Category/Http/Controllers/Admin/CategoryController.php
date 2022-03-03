<?php

namespace App\Modules\Category\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Category\Entities\Category;
use App\Modules\Category\Http\Requests\CategoryRequest;
use App\Modules\Category\Repositories\CategoryRepository;
use App\Modules\Category\Transformers\CategoryDetailResource;
use App\Modules\Category\Transformers\CategoryResource;
use App\Modules\Category\Transformers\CategoryWithAdditionsResource;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Transformers\ProductResource;
use App\Scopes\NormalProductScope;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $categoryRepository;

//    public function __construct(CategoryRepository $categoryRepository)
//    {
//        $this->categoryRepository = $categoryRepository;
//        $this->middleware('permission:category-list', ['only' => ['index', 'show']]);
//        $this->middleware('permission:category-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:category-edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
//    }

    public function index(Request $request)
    {
        $categories = Category::query();
        if (isset($request['type']) && $request['type'] == 'additions') {
            $categories->where('have_additions', 1)->get();
        } elseif (isset($request['type']) && $request['type'] == 'package') {
            $categories->where('is_package', 1)->get();
        } elseif (!isset($request['type'])) {
            $categories->where('have_additions', 0)->where('is_package', 0)->get();;
        }
        $total = $categories->count();
        return $this->apiResponse(['categories' => CategoryWithAdditionsResource::collection($categories->paginate($request['pageLimit'] ?? $total)),
            'total' => $total,
            'page' => $request['page'],
            'pageLimit' => $request['pageLimit'],
            'lastPages' => isset($request['pageLimit']) ? ceil((!empty($categories) ? $total : 0) / $request['pageLimit']) : 0]);
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return $this->apiResponse(new CategoryResource($category));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $category = Category::find($id);
        return $this->apiResponse(new CategoryDetailResource($category));
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::find($id);
        $category->update($request->validated());
        return $this->apiResponse(new CategoryResource($category));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return $this->apiResponse(CategoryResource::collection(Category::all()));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function categoryAdditions($id)
    {
        $additions = Product::withoutGlobalScope(NormalProductScope::class)->where('category_id', $id)->get();
        return $this->apiResponse(ProductResource::collection($additions));
    }
}
