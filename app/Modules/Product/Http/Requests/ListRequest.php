<?php

namespace App\Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
class ListRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|array',
            'name.ar' =>'required|string',
            'name.en' =>'required|string',
            'description' => 'required|array',
            'description.ar' => 'required|string',
            'description.en' => 'required|string',
            'image' =>'required|string',
            'type' =>'required|string',
            'products' => 'required|array',
            'products.*' => 'required|exists:products,id',
            'store_id' => 'nullable|exists:stores,id',
        ];
    }
}
