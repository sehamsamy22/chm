<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\FullUserDetailsResource;
use App\Models\User;
use Doctrine\DBAL\Driver\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;

// use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function facebookRedirect()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->setScopes(['email', 'openid'])->redirect();
    }

    public function loginWithFacebook(Request $request)
    {
        try {
            $user = Socialite::driver('facebook')->stateless()->user();
            $existUser = User::where('provider_id', $user->id)->first();
            if ($existUser) {
                Auth::login($existUser);
                $token = $existUser->createToken('customer-token')->plainTextToken;
                $existUser['token'] = $token;
                return $this->apiResponse(['user' => new FullUserDetailsResource($existUser), 'token' => $token]);
            } else {
                $createUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email ?? $user->name . '@gmail.com',
                    'provider_id' => $user->id,
                    'password' => encrypt('123456')
                ]);

                Auth::login($createUser);
                $token = $createUser->createToken('customer-token')->plainTextToken;
                $locale = $request->header('Content-Language');
                $user->update(['lang' => $locale]);
                //  TODO generate verification_code random
                //TODO send code via sms.
                $createUser->update([
                    'verification_code' => '2222',
                    'image' => 'storage/uploads/thumbnails/4dx4WQ-1622551885.jpg'
                ]);
                $createUser->wallet()->create(['amount' => 0]);
                $createUser['token'] = $token;
                $admins = Admin::role('GeneralManager')->get();
                foreach ($admins as $admin) {
                    Notification::send($admin, new NewCustomer($user));
                }
                return $this->apiResponse(['user' => new FullUserDetailsResource($createUser), 'token' => $token]);
            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }

    public function loginWithGoogle(Request $request)
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            $userExist = User::where('provider_id', $user->id)->first();
            if ($userExist) {
                Auth::login($userExist);
                $token = $userExist->createToken('customer-token')->plainTextToken;
                $userExist['token'] = $token;
                return $this->apiResponse(['user' => new FullUserDetailsResource($userExist), 'token' => $token]);
            } else {
                $createUser = User::create([
                    'name' => $user->name ?? '',
                    'email' => $user->email ?? $user->name . '@gmail.com',
                    'provider_id' => $user->id,
                    'password' => encrypt('123456')
                ]);
                Auth::login($createUser);
                $token = $userExist->createToken('customer-token')->plainTextToken;
                $locale = $request->header('Content-Language');
                $user->update(['lang' => $locale]);
                //  TODO generate verification_code random
                //TODO send code via sms.
                $userExist->update([
                    'verification_code' => '2222',
                    'image' => 'storage/uploads/thumbnails/4dx4WQ-1622551885.jpg'
                ]);
                $userExist->wallet()->create(['amount' => 0]);
                $userExist['token'] = $token;
                $admins = Admin::role('GeneralManager')->get();
                foreach ($admins as $admin) {
                    Notification::send($admin, new NewCustomer($user));
                }
                return $this->apiResponse(['user' => new FullUserDetailsResource($userExist), 'token' => $token]);
            }
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }
}
