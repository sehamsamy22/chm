<?php

namespace App\Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CategoryRequest extends FormRequest
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
            'name.ar' => 'required|string',
            'name.en' => 'required|string',
            'image' => 'required|string',
            'parent_id' => 'nullable|exists:categories,id',
            'store_id' => 'nullable|exists:stores,id',
            'have_additions' => 'nullable|int|in:0,1',
        ];
    }
}
