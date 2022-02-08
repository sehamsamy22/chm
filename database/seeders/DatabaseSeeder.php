<?php

namespace Database\Seeders;

use App\Modules\Ad\Database\Seeders\AdLocationSeeder;
use App\Modules\Payment\Database\Seeders\PaymentMethodsTableSeeder;
use App\Modules\Product\Entities\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
//            PaymentMethodsTableSeeder::class,
//            SetInitialData::class
          AdLocationSeeder::class
        ]);
    }
}
