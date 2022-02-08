<?php

namespace App\Modules\Category\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Modules\Category\Entities\Category;
use App\Modules\Category\Entities\CategoryOption;
use App\Modules\Category\Http\Requests\CategoryOptionRequest;
use App\Modules\Category\Transformers\CategoryOptionDashboardResource;
use App\Modules\Category\Transformers\CategoryOptionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class CategoryOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */


    public function index()
    {
        $categoryOptions = CategoryOption::all();
        return $this->apiResponse(CategoryOptionDashboardResource::collection($categoryOptions));
    }

    /**
     * Store a newly created resource in storage.
     * @param CategoryOptionRequest $request
     * @return JsonResponse
     */
    public function store(CategoryOptionRequest $request)
    {
        $validated = $request->validated();
        $category = Category::find($validated['category_id']);
        $category->categoryOptions()->delete();
        if (!empty($validated['options'])) {
            foreach ($validated['options'] as $option) {
                CategoryOption::create(['option_id' => $option, 'category_id' => $validated['category_id']]);
            }
        }
        return $this->apiResponse(CategoryOptionDashboardResource::collection($category->categoryOptions));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $option = CategoryOption::find($id);
        return $this->apiResponse(new CategoryOptionResource($option));
    }

    public function categoryOptions($id)
    {
        $options = CategoryOption::where('category_id', $id)->get();
        return $this->apiResponse(CategoryOptionDashboardResource::collection($options));
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    /**
     * Update the specified resource in storage.
     * @param CategoryOptionRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CategoryOptionRequest $request, $id)
    {
        $validated = $request->validated();
        $category = Category::find($validated['category_id']);
        $category->categoryOptions()->delete();
        if (!empty($validated['options'])) {
            foreach ($validated['options'] as $option) {
                CategoryOption::create(['option_id' => $option, 'category_id' => $validated['category_id']]);
            }
        }

        return $this->apiResponse(new CategoryOptionDashboardResource($category->categoryOptions));
    }

    /**
     * Remove the specified resource from storage.
     * @param CategoryOption $option
     * @return JsonResponse
     */
    public function destroy($id)
    {
        CategoryOption::find($id)->delete();
        return $this->apiResponse(CategoryOptionDashboardResource::collection(CategoryOption::all()));
    }
}
