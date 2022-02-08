<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function apiResponse($result, $code = 200, $headers = [],$route=null)
    {
        if (null === $result) $code = 204;
        return response()->json($result, $code, [])->withHeaders($headers);
    }

    static function errorResponse($message, $errors = [], $code = 404)
    {
        $response = [
            'message' => $message,
            'status' => false ,
            'errors' => $errors
        ];
        return response()->json($response, $code);
    }
}
