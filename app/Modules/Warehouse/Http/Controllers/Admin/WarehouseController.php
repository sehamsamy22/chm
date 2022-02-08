<?php

namespace App\Modules\Warehouse\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Warehouse\Entities\Warehouse;
use App\Modules\Warehouse\Http\Requests\WarehouseRequest;
use App\Modules\Warehouse\Repositories\WarehouseRepository;
use App\Modules\Warehouse\Transformers\WarehouseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $warehouseRepository;

    public function __construct(WarehouseRepository $warehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;

    }

    public function index()
    {
        $warehouses = $this->warehouseRepository->all();
        return $this->apiResponse(WarehouseResource::collection($warehouses));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(WarehouseRequest $request)
    {
        $warehouse = Warehouse::create($request->validated());
        return $this->apiResponse(new WarehouseResource($warehouse));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $warehouse = Warehouse::find($id);
        return $this->apiResponse(new WarehouseResource($warehouse));
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
    public function update(WarehouseRequest $request, Warehouse $warehouse)
    {
        $warehouse->update($request->validated());
        return $this->apiResponse(new WarehouseResource($warehouse));
    }

    /**
     * Remove the specified resource from storage.
     * @param Ad $ad
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return $this->apiResponse(WarehouseResource::collection(Warehouse::all()));
    }


}
