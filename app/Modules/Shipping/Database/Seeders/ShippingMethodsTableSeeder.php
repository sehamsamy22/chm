<?php

namespace App\Modules\Shipping\Database\Seeders;

use App\Modules\Shipping\Entities\ShippingCredential;
use App\Modules\Shipping\Entities\ShippingMethod;
use Illuminate\Database\Seeder;

class ShippingMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = config('shipping_all_methods');
        foreach ($paymentMethods as $configName => $configMethod) {
            $method = ShippingMethod::updateOrCreate([
                'id' => $configMethod['id'],
            ], [
                'name' => ['ar' => $configMethod['name_ar'], 'en' => $configName],
                'active' => $configMethod['active'],
                'deactivation_notes' => $configMethod['deactivation_notes'],
            ]);

            if (isset($configMethod['credentials'])) {
                foreach ($configMethod['credentials'] as $key => $credential) {
                    $credentialData = [
                        'method_id' => $method->id
                    ];
                    $defaultArr = [];
                    if (is_string($key)) {
                        $credentialData['name'] = $credential;
                    } else {
                        $credentialData['name'] = $credential;
                    }
                    ShippingCredential::updateOrCreate($credentialData, $defaultArr);
                }
            }
            $this->command->info("{$configName} -> seeded");
        }
    }
}
