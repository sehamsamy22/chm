<?php

namespace App\Http\Controllers;

use Facades\App\Services\UploadService;
use Illuminate\Http\Request;

class UploadsController extends Controller
{

    public function upload(Request $request)
    {
        // $request->validate([
        //     'file' => 'mimes:jpeg,bmp,png,pdf,doc,docx',
        // ]);
        $data = UploadService::upload($request);
        return $this->apiResponse($data, 200);
    }
}
