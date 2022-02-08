<?php

namespace App\Modules\Product\Http\Controllers\Admin;


use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\ProductColor;
use App\Modules\Product\Http\Requests\BrandRequest;
use App\Modules\Product\Http\Requests\ListRequest;
use App\Modules\Product\Http\Requests\ProductColorRequest;
use App\Modules\Product\Transformers\BrandResource;
use App\Modules\Product\Transformers\ListResource;
use App\Modules\Product\Transformers\ProductColorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;


class BrandController extends Controller
{
   public function __construct()
   {
       $this->middleware('permission:brand-list', ['only' => ['index', 'show']]);
       $this->middleware('permission:brand-create', ['only' => ['create', 'store']]);
       $this->middleware('permission:brand-edit', ['only' => ['edit', 'update']]);
       $this->middleware('permission:brand-delete', ['only' => ['destroy']]);
   }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {
        $brands = Brand::all();
        return $this->apiResponse(BrandResource::collection($brands));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BrandRequest $request)
    {
        $brand = Brand::create($request->validated());
        return $this->apiResponse(new BrandResource($brand));
    }

    /**
     * Show the specified resource.
     * @param Lists $list
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(BrandRequest $brand)
    {
        return $this->apiResponse(new BrandResource($brand));
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(BrandRequest $request, $id)
    {
        $brand = Brand::find($id);
        $brand->update($request->validated());
        return $this->apiResponse(new BrandResource($brand));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $brand = Brand::find($id);
        $brand->delete();
        return $this->apiResponse(BrandResource::collection(Brand::all()));
    }
}
