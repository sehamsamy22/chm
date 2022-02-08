<?php

namespace App\Modules\Setting\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Setting\Entities\Setting;
use App\Modules\Setting\Transformers\SettingResource;

class SettingController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $settings = Setting::orderBy('order_by', 'asc')->get();
        return $this->apiResponse(SettingResource::collection($settings));
    }

    /**
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($type)
    {
        $customModuleArr = [];
        if (Setting::where('module', $type)->count()) {
            $settings = Setting::where('module', $type)->orderBy('order_by', 'asc')->get();
            return $this->apiResponse(SettingResource::collection($settings));
        } else {
            return $this->apiResponse(['error' => ['Page Not Found.']], 404);
        }
    }


    public function store()
    {
        $requestSettings = request()->all();
        $settings = Setting::whereIn('name', array_keys($requestSettings))->get()->toArray();
        $updatedSettings = [];
        foreach ($settings as $setting) {
            if (array_key_exists($setting['name'], $requestSettings)) {
                //dd($requestSettings[$setting['name']]);
                $setting['value'] = $requestSettings[$setting['name']];
                    (is_array($setting['value']));
                                    $setting['value']= is_array($setting['value'] )?   json_encode($setting['value'],true):$setting['value'];

                $updatedSettings[] = $setting;
            }
        }
    //   dd(is_array($updatedSettings));
      Setting::upsert($updatedSettings, 'id');
      $newsettings= Setting::hydrate($updatedSettings);
     
        return $this->apiResponse(SettingResource::collection($newsettings));
    }
}
