<?php

namespace App\Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Promotion extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'discount' => 'required',
            'paid_quantity' => 'sometimes',
            'discounted_quantity' => 'sometimes',
            'start_date' => 'required',
            'expiration_date' => 'required',
            'list_id' => 'required|exists:lists,id'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
