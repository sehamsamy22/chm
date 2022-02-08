<?php

namespace App\Http\Middleware;

use Closure;

class VerifyCode
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_verified != "1") {
            return response()->json([
                'message' => 'Please Enter Verification Code .'
            ], 404);
        }
        return $next($request);
    }
}
