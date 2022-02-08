<?php

namespace App\Modules\Product\Http\Controllers\Admin;


use App\Modules\Product\Entities\Lists;
use App\Modules\Product\Http\Requests\ListRequest;
use App\Modules\Product\Transformers\ListResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;


class ListController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:list-list', ['only' => ['index', 'show']]);
//        $this->middleware('permission:list-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:list-edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:list-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {
        $lists = Lists::all();
        return $this->apiResponse(ListResource::collection($lists));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ListRequest $request)
    {
        $list = Lists::create(Arr::except($request->validated(), 'products'));
        $list->products()->sync($request['products']);
        return $this->apiResponse(new ListResource($list));
    }

    /**
     * Show the specified resource.
     * @param Lists $list
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Lists $list)
    {
        return $this->apiResponse(new ListResource($list));
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
    public function update(ListRequest $request, $id)
    {
        $list = Lists::find($id);
        $list->update($request->validated());
        $list->products()->sync($request['products']);
        return $this->apiResponse(new ListResource($list));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $list = Lists::find($id);
        $list->delete();
        return $this->apiResponse(ListResource::collection(Lists::all()));
    }
}
