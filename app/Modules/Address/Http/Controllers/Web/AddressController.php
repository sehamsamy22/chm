<?php

namespace App\Modules\Address\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Address\Entities\Address;
use App\Modules\Address\Entities\Area;
use App\Modules\Address\Entities\City;
use App\Modules\Address\Entities\Currency;
use App\Modules\Address\Http\Requests\AddressRequest;
use App\Modules\Address\Repositories\AddressRepository;
use App\Modules\Address\Transformers\AddressResource;
use App\Modules\Address\Transformers\AreaResource;
use App\Modules\Address\Transformers\CityResource;
use App\Modules\Address\Transformers\CurrencyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


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
        $addresses = Address::where('user_id', auth()->user()->id)->with(['area','area.city','area.city.country','user'])->get();
        return $this->apiResponse(AddressResource::collection($addresses));
    }

    /**
     * Store a newly created resource in storage.
     * @param AddressRequest $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $address= $user->createAddressFromMap($request['address']);
        if (count(auth()->user()->addresses) == 1) {
            $address->update(['default' => 1]);
        }
        return $this->apiResponse(new AddressResource($address));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $address = Address::find($id);
        return $this->apiResponse(new AddressResource($address));
    }

    /**
     * Update the specified resource in storage.
     * @param AddressRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(AddressRequest $request, $id)
    {
        $address = Address::find($id);
        $inputs = $request->validated();
        $inputs['user_id'] = auth()->user()->id;
        $address->update($inputs);
        return $this->apiResponse(new AddressResource($address));
    }

//    /**
    public function setDefaultAddress(Request $request, $id)
    {
        $data = $request->validate(['default' => 'required|boolean']);
        $address = Address::findOrFail($id);
        $address->update($data);
        Auth()->user()->addresses()->where('id', '!=', $id)->update(['default' => 0]);
        return $this->apiResponse(new AddressResource($address));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();
        return $this->apiResponse(AddressResource::collection(auth()->user()->addresses));
    }

    public function cities($id)
    {
        $cities = City::where('country_id', $id)->with("areas")->get();
        return $this->apiResponse(CityResource::collection($cities));
    }

    public function areas($id)
    {
        $areas = Area::where('city_id', $id)->get();
        return $this->apiResponse(AreaResource::collection($areas));
    }
    public function currencies()
    {
        $currencies = Currency::all();
        return $this->apiResponse(CurrencyResource::collection($currencies));
    }
}
