<?php

namespace App\Modules\Address\Http\Controllers\Admin;

use App\Modules\Address\Entities\City;
use App\Modules\Address\Entities\Country;
use App\Modules\Address\Http\Requests\CityRequest;
use App\Modules\Address\Http\Requests\AreaRequest;
use App\Modules\Address\Repositories\CityRepository;
use App\Modules\Address\Repositories\AreaRepository;
use App\Modules\Address\Transformers\CityResource;
use App\Modules\Address\Transformers\AreaResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;


class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $CityRepository;

    public function __construct(CityRepository $CityRepository)
    {
        $this->CityRepository = $CityRepository;
        $this->middleware('permission:city-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:city-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:city-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:city-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $cities = City::where('country_id',app('Illuminate\Http\Request')->header('country_id'))->get();
        return $this->apiResponse(CityResource::collection($cities));
    }

    /**
     * Store a newly created resource in storage.
     * @param CityRequest $request
     * @return JsonResponse
     */
    public function store(CityRequest $request)
    {
        $city = City::create($request->validated());
        return $this->apiResponse(new CityResource($city));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $city = City::find($id);
        return $this->apiResponse(new CityResource($city));
    }

    /**
     * Update the specified resource in storage.
     * @param CityRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CityRequest $request, $id)
    {
        $city = City::find($id);
        $city->update($request->validated());
        return $this->apiResponse(new CityResource($city));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();
        return $this->apiResponse(CityResource::collection(City::all()));
    }
}
