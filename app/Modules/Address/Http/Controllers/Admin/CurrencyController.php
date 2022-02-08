<?php

namespace App\Modules\Address\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Address\Entities\Country;
use App\Modules\Address\Entities\Currency;
use App\Modules\Address\Http\Requests\CountryRequest;
use App\Modules\Address\Http\Requests\CurrenyRequest;
use App\Modules\Address\Repositories\CountryRepository;
use App\Modules\Address\Transformers\CountryResource;
use App\Modules\Address\Transformers\CurrencyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;


class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */


    public function index()
    {
        $currencies = Currency::all();
        return $this->apiResponse(CurrencyResource::collection($currencies));
    }

    /**
     * Store a newly created resource in storage.
     * @param CurrenyRequest $request
     * @return JsonResponse
     */
    public function store(CurrenyRequest $request)
    {
        $currency = Currency::create($request->validated());
        return $this->apiResponse(new CurrencyResource($currency));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $currency = Currency::find($id);
        return $this->apiResponse(new CurrencyResource($currency));
    }

    /**
     * Update the specified resource in storage.
     * @param CurrenyRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CurrenyRequest $request, $id)
    {
        $currency = Currency::find($id);
        $currency->update($request->validated());
        return $this->apiResponse(new CurrencyResource($currency));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $currency = Currency::findOrFail($id);
        $currency->delete();
        return $this->apiResponse(CurrencyResource::collection(Country::all()));
    }
}
