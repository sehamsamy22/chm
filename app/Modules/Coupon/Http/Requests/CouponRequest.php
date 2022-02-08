<?php

namespace App\Modules\Coupon\Http\Requests;

use App\Modules\Product\Entities\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class CouponRequest extends FormRequest
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
    public function rules(Request $request)
    {
        if ($this->method() == 'PUT') {
            $prom ='required|string|unique:coupons,prom_code,' . $this->coupon;
        } else {
            $prom ='required|string|unique:coupons,prom_code';
        }
        return [
            'name' => 'required|array',
            'name.ar' => 'required|string',
            'name.en' => 'required|string',
            'prom_code' => $prom,
            'type' => 'required|in:percent,amount',
            'prom_time' => 'required|in:once,daily,weekly,monthly',
            'start_date' => 'required',
            'end_date' => 'required',
            'amount' => 'required',
            'max_limit' => 'required|integer',
            'coupon_customization_type' => 'nullable',
            'coupon_customization_ids.*' => ['sometimes', Rule::requiredIf(isset($request->coupon_customization_type)), "exists:{$request->coupon_customization_type},id"],
        ];
    }
}
