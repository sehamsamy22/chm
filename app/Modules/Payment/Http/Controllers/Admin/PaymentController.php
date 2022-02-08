<?php

namespace App\Modules\Payment\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Payment\Entities\PaymentMethod;
use App\Modules\Payment\Transformers\PaymentResource;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:payment-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:payment-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:payment-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:payment-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $payments = PaymentMethod::all();
        return $this->apiResponse(PaymentResource::collection($payments));
    }

    public function show($id)
    {
        $payment = PaymentMethod::find($id);
        return $this->apiResponse(new PaymentResource($payment));
    }

    public function activeToggle($id)
    {
        $payment = PaymentMethod::findOrFail($id);
        if ($payment->deactivated_at) {
            $payment->deactivated_at = null;
        } else {
            $payment->deactivated_at = now();
        }
        $payment->save();
        return $this->apiResponse(new PaymentResource($payment));
    }

    public function update(Request $request, $id)
    {
        $payment = PaymentMethod::find($id);
        if (isset($request->credentials)) {
            foreach ($payment->credentials as $credential) {
                if ($credential->key == $request->credentials['key']) {
                    $credential->update(['value' => $request->credentials['value']]);
                }
            }
        }
        return $this->apiResponse(new PaymentResource($payment));
    }
}
