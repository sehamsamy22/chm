<?php

namespace App\Modules\Address\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
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
            'name' => 'required|array',
            'name.ar' =>'required|string',
            'name.en' =>'required|string',
            'currency_id' => 'required|exists:currencies,id',
            'flag' => 'required',
        ];
    }
}
