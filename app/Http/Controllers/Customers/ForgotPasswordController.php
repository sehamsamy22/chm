<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function postEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        $token = Str::random(64);
        DB::table('password_resets')->insert(
            ['email' => $request->email,
                'token' => $token,
                'guard_name' => 'user-api',
                'created_at' => Carbon::now()]
        );
        $url = Config::get('app.front_url');
        Mail::send('email.forgotPassword_email', ['token' => $token, 'url' => $url, 'email' => $request->email], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password Notification');
        });
        return response()->json(['message' => 'We have e-mailed your password reset link!']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);
        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token,
                'guard_name' => 'user-api'
            ])
            ->first();
        if (!$updatePassword)
            return redirect(Config::get('app.front_url') . '/user-reset-password?success=false');
        $admin = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);
        DB::table('password_resets')->where(['email' => $request->email])->delete();
        return response()->json(['message' => 'you reset password successfully.']);
    }
}
