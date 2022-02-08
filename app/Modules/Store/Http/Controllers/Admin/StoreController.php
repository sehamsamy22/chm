<?php

namespace App\Modules\Store\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Address\Http\Requests\CountryRequest;
use App\Modules\Store\Entities\Store;
use App\Modules\Store\Http\Requests\StoreRequest;
use App\Modules\Store\Repositories\StoreRepository;
use App\Modules\Store\Transformers\StoreResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private StoreRepository $storeRepository;

    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public function index()
    {
        $stores = $this->storeRepository->all();
        return $this->apiResponse(StoreResource::collection($stores));
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $store = Store::create($request->validated());
        return $this->apiResponse(new StoreResource($store));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $store = Store::find($id);
        return $this->apiResponse(new StoreResource($store));
    }

    /**
     * Update the specified resource in storage.
     * @param StoreRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(StoreRequest $request, $id)
    {
        $store = Store::find($id);
        $store->update($request->validated());
        return $this->apiResponse(new StoreResource($store));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $store = Store::findOrFail($id);

        $store->delete();
        return $this->apiResponse(StoreResource::collection(Store::all()));
    }
}
