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
            'size_id' => 'nullable|exists:subscription_sizes,id',
            'type_id' => 'nullable|exists:subscription_types,id',
            'delivery_id' => 'nullable|exists:subscription_delivery_counts,id',
            'wrapping_type_id' => 'nullable|exists:wrapping_types,id',
            'day_count_id' => 'nullable|exists:subscription_day_counts,id',
            'time_id' => 'nullable|exists:pickup_times,id',
             'normal_subscription_id' => 'nullable|exists:normal_subscriptions,id',
            'items' => 'nullable|array',
//            'items.*.quantity' => 'required|max:',
            'items.*.product_id' => 'nullable|exists:products,id',
        ];
    }
}
