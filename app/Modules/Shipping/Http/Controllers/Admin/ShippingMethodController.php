<?php

namespace App\Modules\Shipping\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Payment\Entities\PaymentMethod;
use App\Modules\Shipping\Entities\ShippingMethod;
use App\Modules\Shipping\Transformers\PaymentResource;
use App\Modules\Shipping\Transformers\ShippingMethodResource;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{

    public function index()
    {
        $ShippingMethods = ShippingMethod::all();
        return $this->apiResponse(ShippingMethodResource::collection($ShippingMethods));
    }


    public function update(Request $request, $id)
    {
        $ShippingMethod = ShippingMethod::find($id);
        if (count($request->credentials)) {
            foreach ($ShippingMethod->credentials as $credential) {
                if ($credential->name == $request->credentials['name']) {
                    $credential->update(['value' => $request->credentials['value']]);
                }
            }
        }
        return $this->apiResponse(new ShippingMethodResource($ShippingMethod));
    }

}
