<?php
namespace App\Modules\Blog\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'admin_id' => 'required|exists:admins,id',
            'category_id' => 'required|exists:blog_categories,id',
            'image' => 'nullable|',

        ];
    }
}
