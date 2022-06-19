<?php

namespace App\Modules\Cart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

             'subscription_id' => 'nullable',
            'type' => 'sometimes|in:custom,normal',
            'items' => 'nullable|array',
//            'items.*.quantity' => 'required|max:',
            'items.*.product_id' => 'nullable|exists:products,id',
        ];
    }
}
