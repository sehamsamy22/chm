<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class DashboardLocalization
{
    /**
     * Localization constructor.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!in_array("admincp", $request->segments()))  return $next($request);
        // read the language from the request header

        $locale = $request->header('Content-Language');
        // if the header is missed
        if(!$locale){
            // take the default local language
            if(Auth::check())   $locale =Auth::guard('admin-api')->user()->lang;
        }
        // check the languages defined is supported
//        if (!array_key_exists($locale, $this->app->config->get('app.supported_languages'))) {
//            // respond with error
//            return response()->json(['error' => 'Language not supported.'], 403);
//        }
        // set the local language
        $this->app->setLocale($locale);
        // get the response after the request is done
        $response = $next($request);
        // set Content Languages header in the response
        $response->headers->set('Content-Language', $locale);

//        dd($locale);
//        if(Auth::check())
//            Auth::user()->update(['lang' => $locale]);
        // return the response

        return $response;
    }
}
