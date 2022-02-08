<?php

namespace App\Modules\Order\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Blog\Http\Requests\FeatureRequest;
use App\Modules\Order\Entities\PickupTime;
use App\Modules\Order\Http\Requests\PickupTimeRequest;
use App\Modules\Order\Transformers\PickupTimeResource;

class PickupTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */


    public function index()
    {
        $pickupTimes = PickupTime::all();
        return $this->apiResponse((PickupTimeResource::collection($pickupTimes)));
    }


    public function store(PickupTimeRequest $request)
    {
        $pickupTime = PickupTime::create($request->validated());
        return $this->apiResponse(new PickupTimeResource($pickupTime));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $pickupTime = PickupTime::find($id);
        return $this->apiResponse(new PickupTimeResource($pickupTime));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(PickupTimeRequest $request, $id)
    {
        $pickupTime = PickupTime::find($id);
        $pickupTime->update($request->validated());
        return $this->apiResponse(new PickupTimeResource($pickupTime));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $pickupTime = PickupTime::findOrFail($id);
        $pickupTime->delete();
        return $this->apiResponse(PickupTimeResource::collection(PickupTime::all()));
    }
    
    public function toggleTime($id)
    {
        $pickupTime = PickupTime::findOrFail($id);
         if($pickupTime->available == 1)
             {  
             $pickupTime->update(["available" => 0]);
            }else{
            $pickupTime->update(["available" => 1]);
            }
        return $this->apiResponse(new PickupTimeResource($pickupTime));
    }
}
