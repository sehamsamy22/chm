<?php

namespace App\Modules\Product\Http\Controllers\Admin;


use App\Modules\Product\Entities\ProductColor;
use App\Modules\Product\Http\Requests\ListRequest;
use App\Modules\Product\Http\Requests\ProductColorRequest;
use App\Modules\Product\Transformers\ListResource;
use App\Modules\Product\Transformers\ProductColorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;


class ProductColorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:color-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:color-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:color-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:color-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {
        $colors = ProductColor::all();
        return $this->apiResponse(ProductColorResource::collection($colors));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProductColorRequest $request)
    {
        $color = ProductColor::create($request->validated());
        return $this->apiResponse(new ProductColorResource($color));
    }

    /**
     * Show the specified resource.
     * @param Lists $list
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ProductColor $color)
    {
        return $this->apiResponse(new ProductColorResource($color));
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
    public function update(ProductColorRequest $request, $id)
    {
        $color = ProductColor::find($id);
        $color->update($request->validated());
        return $this->apiResponse(new ProductColorResource($color));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $color = ProductColor::find($id);
        $color->delete();
        return $this->apiResponse(ProductColorResource::collection(ProductColor::all()));
    }
}
