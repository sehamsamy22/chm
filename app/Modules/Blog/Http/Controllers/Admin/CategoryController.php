<?php

namespace App\Modules\Blog\Http\Controllers\Admin;

use App\Modules\Blog\Entities\BlogCategory;
use App\Modules\Blog\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

use App\Modules\Blog\Transformers\CategoryResource;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:blog-category-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:blog-category-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:blog-category-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:blog-category-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */


    public function index()
    {
        $categories = BlogCategory::all();
        return $this->apiResponse(CategoryResource::collection($categories));
    }


    public function store(CategoryRequest $request)
    {

        $category = BlogCategory::create($request->validated());
        return $this->apiResponse(new CategoryResource($category));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $category = BlogCategory::find($id);
        return $this->apiResponse(new CategoryResource($category));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */


    public function update(CategoryRequest $request, $id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->update($request->validated());
        return $this->apiResponse(new CategoryResource($category));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->delete();
        return $this->apiResponse(CategoryResource::collection(BlogCategory::all()));
    }
}
