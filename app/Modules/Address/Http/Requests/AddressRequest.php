<?php

namespace App\Modules\Address\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
//          'user_id' => 'required|exists:users,id',
            'area_id' => 'required|exists:areas,id',
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'lat' => 'required|string',
            'lng' => 'required|string',
            'default' => "sometimes|boolean"
        ];
    }
}
