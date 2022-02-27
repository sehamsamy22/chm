<?php

namespace App\Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProductRequest extends FormRequest
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
            $SKU = 'required|string|unique:products,SKU,' . $this->product;

        } else {
            $SKU = 'required|string|unique:products,SKU';
        }
        return [
            'name' => 'required|array',
            'name.ar' => 'required|string',
            'name.en' => 'required|string',
            'description' => 'required|array',
            'description.ar' => 'required|string',
            'description.en' => 'required|string',
            'image' => 'required|string',
            'SKU' => $SKU,
            'stock' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'max_per_order' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'discount_start_date' => 'nullable|date',
            'discount_end_date' => 'nullable|date',
            'deactivated_at' => 'nullable|date',
            'deactivation_notes' => 'nullable|string',
            'digit' => 'required|boolean',
            'is_package' => 'nullable|int|in:0,1',
            'bundle' => 'sometimes|boolean',
            'type' => 'sometimes|in:subscription,normal,service,additions,package_addition',
            'time_period' => 'required_if:type,subscription',
//            'options_values' => ['nullable', 'array', Rule::requiredIf($request->bundle != 1)],
//            'options_values.*.option_id' =>'required|exists:options,id',
//            'options_values.*.color_id' => 'sometimes|exists:product_colors,id',
            'tags' => 'nullable|array',
            'images' => 'nullable|array',
            'bundle_products' => 'required_if:bundle,==,1|array',
            'bundle_products.*' => 'exists:products,id',
            'package_categories' => 'required_if:is_package,==,1|array',
            'package_categories.*' => 'exists:categories,id'
        ];
    }
}
