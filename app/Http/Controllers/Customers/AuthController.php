<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\FullUserDetailsResource;
use App\Models\Admin;
use App\Models\User;
use App\Notifications\NewCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'image' => 'nullable|string',
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $user = User::create($validatedData);
        $token = $user->createToken('customer-token')->plainTextToken;
        $locale = $request->header('Content-Language');
        $user->update(['lang' => $locale]);
        //  TODO generate verification_code random
        // $verification_code = rand(1000, 10000);
        //TODO send code via sms.
        $user->update([
            'verification_code' => '2222',
            'image' => 'storage/uploads/thumbnails/4dx4WQ-1622551885.jpg'
        ]);
        $user->wallet()->create(['amount'=>0]);
        $user['token'] = $token;
        $admins= Admin::role('GeneralManager')->get();
        foreach ($admins AS $admin){
            Notification::send($admin, new NewCustomer($user));
        }
        return $this->apiResponse(['user' => new FullUserDetailsResource($user), 'token' => $token]);
    }

    public function login(Request $request)
    {
        if (preg_match("/^([a-z0-9\+7101998_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $request->email)) {
            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return response()->json([
                    'message' => 'Invalid login details'
                ], 401);
            }
            $user = User::where('email', $request['email'])->firstOrFail();
        } elseif (preg_match("/^([0-9\s\-\+\(\)]*)$/", $request->email)) {
            if (!Auth::attempt(['phone' => $request->email, 'password' => $request->password])) {
                return response()->json([
                    'message' => 'Invalid login details'
                ], 401);
            }
            $user = User::where('phone', $request['email'])->firstOrFail();
        }
        $locale = $request->header('Content-Language');

        $user->update(['lang' => $locale]);
        $token = $user->createToken('customer-token')->plainTextToken;
        $user['token'] = $token;
        return $this->apiResponse(['user' => new FullUserDetailsResource($user), 'token' => $token]);
    }

    public function profile(Request $request)
    {

        return $this->apiResponse(new FullUserDetailsResource($request->user()));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:4',
            'phone' => 'required',
        ]);
        $user = User::where('phone', request('phone'))->first();
        if (!$user) return response()->json(['message' => 'Invalid Phone Number'], 401);
        if ($user->verification_code != request('code')) return response()->json(['message' => 'Invalid Verification Code'], 401);
        $token = $user->createToken('customer-token')->plainTextToken;
//        $user['token'] = $token;
        $user->update([
            'is_verified' => 1,
            'token' => $token
        ]);
        return $this->apiResponse(['user' => new FullUserDetailsResource($user), 'token' => $token]);
    }

    public function resendVerificationCode(Request $request)
    {
        $validated = $request->validate(['phone' => 'required|string|exists:users,phone']);
        $user = User::where('phone', $validated['phone'])->first();
        if ($user) {
//            $code = mt_rand(1000, 10000);
            $user->update(['verification_code' => "2222"]);
            $message = " برجاء إدخال الكود المرفق لتفعيل حسابك الكود:" . "2222";
            //TODO send code via sms
            //SendMessage(request('phone'), $message);
            return response()->json([
                'message' => 'code is sending to you successfully'
            ], 200);
        }
        response()->json(['message' => 'Invalid Phone Number'], 401);
    }

    public function logout(Request $request)
    {
//dd(auth()->user());
//        auth()->user()->tokens()->delete();
        auth()->user()->tokens()->where('id', auth()->id())->delete();

        return $this->apiResponse(['message' =>'logout Successfully']);
    }
}
