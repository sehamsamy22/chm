<?php

namespace App\Modules\Page\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
            'title' => 'required|array',
            'title.ar' =>'required|string',
            'title.en' =>'required|string',
            'description' => 'required|array',
            'description.ar' =>'required|string',
            'description.en' =>'required|string',
            'url' => 'nullable|url',
            'image' => 'nullable',
        ];
    }
}
