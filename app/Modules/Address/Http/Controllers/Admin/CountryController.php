<?php

namespace App\Modules\Address\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Address\Entities\Country;
use App\Modules\Address\Http\Requests\CountryRequest;
use App\Modules\Address\Repositories\CountryRepository;
use App\Modules\Address\Transformers\CountryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;


class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function index()
    {
        $countries = $this->countryRepository->all();
        return $this->apiResponse(CountryResource::collection($countries));
    }

    /**
     * Store a newly created resource in storage.
     * @param CountryRequest $request
     * @return JsonResponse
     */
    public function store(CountryRequest $request)
    {
        $country = Country::create($request->validated());
        return $this->apiResponse(new CountryResource($country));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $country = Country::find($id);
        return $this->apiResponse(new CountryResource($country));
    }

    /**
     * Update the specified resource in storage.
     * @param CountryRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CountryRequest $request, $id)
    {
        $country = Country::find($id);
        $country->update($request->validated());
        return $this->apiResponse(new CountryResource($country));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();
        return $this->apiResponse(CountryResource::collection(Country::all()));
    }
}
