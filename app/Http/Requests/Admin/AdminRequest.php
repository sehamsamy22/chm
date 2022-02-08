<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminRequest extends FormRequest
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
            $email = 'required|email|unique:admins,email,' . $this->admin;
            $password = 'nullable|confirmed';

        } else {
            $email = 'required|email|unique:admins,email';
            $password = 'required|confirmed';
        }
        $data = [
            'name' => 'required|string',
            'email' => $email,
            'roles' => 'required',
        ];
        if (request('password')) $data['password'] = $password;
        return $data;
    }
}
