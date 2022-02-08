<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminInfoResource;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if (Admin::count()) return $this->errorResponse('There is admin already registered');

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
        $user = Admin::create($validatedData);
        $locale = $request->header('Content-Language');
        $user->update(['lang' => $locale]);
        $token = $user->createToken('admin-token')->plainTextToken;
        return $this->apiResponse(['admin' => $user, 'token' => $token]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $auth = auth()->guard('admin')->attempt($request->only('email', 'password'));
        if (!$auth) return $this->errorResponse('Email and Password are Wrong.', [], 401);
        $admin = Auth::guard('admin')->user();
        $adminData = collect(new AdminInfoResource($admin));
        $token = $admin->createToken('admin-token')->plainTextToken;
        $adminData->put('token', $token);

        return $this->apiResponse($adminData, 200);
    }

    public function profile(Request $request)
    {
        $admin = Auth::guard('admin-api')->user();
        return $this->apiResponse(new AdminInfoResource($admin));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin-api')->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
        ]);
        $admin->update($request->all());
        return $this->apiResponse(new AdminResource($admin));
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return $this->apiResponse(null, 204);
    }

    public function change_password(Request $request)
    {
        $validatedData = $request->validate([
            'old_password' => 'required|string|min:6',
            'password' => 'required|string|confirmed|min:6',
        ]);
        $admin = auth('admin-api')->user();
        if ($request->old_password != null && !\Hash::check($request->old_password, $admin->password)) {
            return response()->json([
                'message' => 'Invalid  old password'
            ]);
        } else {
            $admin->update([
                'password' => Hash::make($validatedData['password'])
            ]);
            return response()->json(['message' => 'Admin successfully changed password']);

        }

    }

}
