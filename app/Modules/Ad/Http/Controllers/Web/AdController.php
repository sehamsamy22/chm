<?php

namespace App\Modules\Ad\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Ad\Entities\Ad;
use App\Modules\Ad\Transformers\AdResource;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAds($LocationName)
    {
        if ($LocationName == 'banner') {
            $ads = Ad::whereHas('location', function ($q) use ($LocationName) {
                $q->where('name', $LocationName);
            })->get();
            return $this->apiResponse(AdResource::collection($ads));
        }
        if ($LocationName == 'banner-middle') {
            $ads = Ad::whereHas('location', function ($q) use ($LocationName) {
                $q->where('name', $LocationName);
            })->first();
            return $this->apiResponse(new AdResource($ads));
        }
    }
        public function ads()
    {
            $ads = Ad::all();
            return $this->apiResponse(AdResource::collection($ads));
    }


}
