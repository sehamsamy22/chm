<?php

namespace App\Modules\Setting\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Transformers\SettingResource;

class SettingController extends Controller
{
    public function setting($name = null)
    {
        $setting = Setting::query();
        if ($name) {
            $setting = $setting->where('name', $name)->first();
            return $this->apiResponse(new SettingResource($setting));
        }
        $setting = $setting->get();
        return $this->apiResponse(SettingResource::collection($setting));
    }

}
