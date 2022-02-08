<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class OccasionRequest extends FormRequest
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
//        'name', 'description', 'date','type','user_id','isRecurring'
        return [
//          'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'type' => 'required|in:Birthday,Anniversary,Mother Day,ValentineDay,New year',
            'isRecurring' => "sometimes|boolean"
        ];
    }
}
