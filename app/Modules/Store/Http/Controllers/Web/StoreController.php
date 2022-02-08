<?php

namespace App\Modules\Store\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use App\Modules\Address\Entities\Country;
use App\Modules\Address\Transformers\CountryResource;
use App\Modules\Store\Entities\Store;
use App\Modules\Store\Transformers\StoreResource;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStores()
    {
        $countries = Country::whereHas('stores')->get();
        return $this->apiResponse(CountryResource::collection($countries));
    }


}
