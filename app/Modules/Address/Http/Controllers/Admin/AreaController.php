<?php

namespace App\Modules\Address\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Address\Entities\Area;
use App\Modules\Address\Http\Requests\AreaRequest;
use App\Modules\Address\Repositories\AreaRepository;
use App\Modules\Address\Transformers\AreaResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $AreaRepository;

    public function __construct(AreaRepository $AreaRepository)
    {
        $this->AreaRepository = $AreaRepository;
        $this->middleware('permission:area-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:area-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:area-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:area-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $areas = Area::whereHas('city', function ($q) {
            $q->where('country_id', app('Illuminate\Http\Request')->header('country_id'));
        })->get();
        return $this->apiResponse(AreaResource::collection($areas));
    }

    /**
     * Store a newly created resource in storage.
     * @param AreaRequest $request
     * @return JsonResponse
     */
    public function store(AreaRequest $request)
    {
        $area = Area::create($request->validated());
        return $this->apiResponse(new AreaResource($area));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $area = Area::find($id);
        return $this->apiResponse(new AreaResource($area));
    }

    /**
     * Update the specified resource in storage.
     * @param AreaRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(AreaRequest $request, $id)
    {
        $area = Area::find($id);
        $area->update($request->validated());
        return $this->apiResponse(new AreaResource($area));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $area = Area::findOrFail($id);
        $area->delete();
        return $this->apiResponse(AreaResource::collection(Area::all()));
    }
}
