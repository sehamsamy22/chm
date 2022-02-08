<?php

namespace App\Modules\Ad\Database\Seeders;

use App\Modules\Ad\Entities\AdLocation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AdLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        foreach (config('AdLocations')['locations'] as $module) {
            AdLocation::create(['name'=>$module]);
        }

    }
}
