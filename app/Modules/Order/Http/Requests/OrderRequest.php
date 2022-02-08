<?php

namespace App\Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class OrderRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        $request['store_id'] = (int)$request->header('store_id');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $request['user_id'] = auth()->user()->id;
        return [
            'address_id' => 'nullable|exists:addresses,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'prom_code' => 'sometimes|string',
            'received_name' => 'sometimes|string',
            'gift_url' => 'sometimes|url',
            'shipping_method_id' => 'sometimes|exists:shipping_methods,id',
//          'store_id' => 'nullable|exists:stores,id',
        ];
    }
}
