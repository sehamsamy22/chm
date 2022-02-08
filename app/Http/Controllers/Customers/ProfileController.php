<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\FullUserDetailsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = Auth::user();
        $user['token'] =  $request->bearerToken();
        return $this->apiResponse(new FullUserDetailsResource($user));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'image' => 'nullable',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|unique:users,phone,' . $user->id,
        ]);
        $user->update($validatedData);
        $user['token'] =  $request->bearerToken();
        return $this->apiResponse(['user' => new FullUserDetailsResource($user)]);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();
        $validatedData = $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $validatedData['password'] = Hash::make($request->password);
//        dd($request->password,$validatedData);
        $user->update([
            "password"=>    $validatedData['password']
        ]
        );
        return $this->apiResponse(['user' => new FullUserDetailsResource($user)]);
    }

}
