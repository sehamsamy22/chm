<?php

namespace App\Modules\Ad\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Ad\Entities\Ad;
use App\Modules\Ad\Entities\AdLocation;
use App\Modules\Ad\Http\Requests\AdRequest;
use App\Modules\Ad\Repositories\AdRepository;
use App\Modules\Ad\Transformers\AdResource;
use App\Modules\Category\Transformers\CategoryResource;
use App\Modules\Product\Transformers\ListResource;
use App\Modules\Product\Transformers\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $adRepository;

    public function __construct(AdRepository $adRepository)
    {
        $this->adRepository = $adRepository;
//        $this->middleware('permission:ad-list', ['only' => ['index', 'show']]);
//        $this->middleware('permission:ad-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:ad-edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:ad-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $ads = $this->adRepository->all();
        return $this->apiResponse(AdResource::collection($ads));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AdRequest $request)
    {

        $ad = $this->adRepository->create($request->validated());
        return $this->apiResponse(new AdResource($ad));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $ad = Ad::find($id);
        return $this->apiResponse(new AdResource($ad));
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
    public function update(AdRequest $request, $id)
    {
        $ad = $this->adRepository->update($request->validated(), $id);
        return $this->apiResponse(new AdResource($ad));
    }

    /**
     * Remove the specified resource from storage.
     * @param Ad $ad
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Ad $ad)
    {
        $ad->delete();
        return $this->apiResponse(AdResource::collection(Ad::all()));
    }

    public function adLocations()
    {
        $adLocations = AdLocation::all()->map(function ($adLocation) {
            return [
                'id' => $adLocation->id,
                'name' => $adLocation->name,
            ];
        });
        return $this->apiResponse($adLocations);

    }

    public function filtration(Request $request, $type)
    {
        if ($type == 'product') return $this->apiResponse(ProductResource::collection($this->adRepository->filterProducts($request->all())));
        if ($type == 'category') return $this->apiResponse(CategoryResource::collection($this->adRepository->filterCategories($request->all())));
        if ($type == 'lists') return $this->apiResponse(ListResource::collection($this->adRepository->filterLists($request->all())));
    }
}
