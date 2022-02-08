<?php

namespace App\Modules\Ad\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {

        $request['store_id'] = (int)$request->header('store_id');
//        dd($request);
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
            'model_type' => 'nullable',
            'model_id' => 'nullable',
            'image' => 'required|string',
            'order' => 'required|integer',
            'start_date' => 'required|date',
            'expired_at' => 'required|date',
            'external_url' => 'nullable',
            'location_id' => 'required|exists:ad_locations,id',
            'store_id' => 'nullable|exists:stores,id',
            'title' => 'required|array',
            'title.ar' => 'required|string',
            'title.en' => 'required|string',
            'description' => 'required|array',
            'description.ar' => 'required|string',
            'description.en' => 'required|string',
        ];
    }
}
