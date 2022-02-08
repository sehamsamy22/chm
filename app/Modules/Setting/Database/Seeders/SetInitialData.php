<?php

namespace App\Modules\Setting\Database\Seeders;

use App\Modules\Setting\Entities\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SetInitialData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allSettings = config('all_settings');
        foreach ($allSettings as $setting){
            Setting::updateOrCreate([
                'name' => $setting['name']
            ], [
                'value' =>$setting['value'],
                'type' => $setting['type'],
                "module" => $setting['module'],
                "order_by" =>$setting['order_by']
            ]);
        }

    }
}
