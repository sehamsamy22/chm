<?php

namespace App\Modules\Address\Http\Controllers\Admin;

use App\Modules\Address\Entities\Address;
use App\Modules\Address\Entities\Area;
use App\Modules\Address\Entities\City;
use App\Modules\Address\Http\Requests\AddressRequest;
use App\Modules\Address\Repositories\AddressRepository;
use App\Modules\Address\Transformers\AddressResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;


class   AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $AddressRepository;

    public function __construct(AddressRepository $AddressRepository)
    {
        $this->AddressRepository = $AddressRepository;
    }

    public function index()
    {
        $addresses = $this->AddressRepository->all();
        return $this->apiResponse(AddressResource::collection($addresses));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AddressRequest $request)
    {
        $address = Address::create($request->validated());
        return $this->apiResponse(new AddressResource($address));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $address = Address::find($id);
        return $this->apiResponse(new AddressResource($address));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AddressRequest $request, $id)
    {
        $address = Address::find($id);
        $address->update($request->validated());
        return $this->apiResponse(new AddressResource($address));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();
        return $this->apiResponse(AddressResource::collection(Address::all()));
    }

    public function userAddresses($id)
    {
        $addresses = Address::where('user_id', $id)->get();
        return $this->apiResponse(AddressResource::collection($addresses));

    }

    public function quickEditShippingPrice(Request $request)
    {

        if ($request->has('areas')) {
            $areas = Area::whereIn('id', $request['areas'])->update(['shipping_price' => $request['shipping_price']]);
        } else if ($request->has('cities')) {
            $cities = City::whereIn('id', $request['cities'])->update(['shipping_price' => $request['shipping_price']]);
        }
        return $this->apiResponse('Quick edit is done successfully');

    }
}
