<?php

namespace App\Modules\Payment\Database\Seeders;

use App\Modules\Payment\Entities\PaymentCredential;
use App\Modules\Payment\Entities\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentMethods = config('payment_all_methods');
        foreach ($paymentMethods as $configName => $configMethod) {
            $method = PaymentMethod::updateOrCreate([
                'id' => $configMethod['id'],
            ], [
                'name' => ['ar' => $configMethod['name_ar'], 'en' => $configName],
                'image' => $configMethod['image'],
                'is_online' => $configMethod['is_online'],
                'deactivated_at' => $configMethod['active'] ? null : now()
            ]);
            if (isset($configMethod['credentials'])) {
                foreach ($configMethod['credentials'] as $key => $credential) {
                    $credentialData = [
                        'payment_method_id' => $method->id
                    ];
                    $defaultArr = [];
                    if (is_string($key)) {
                        $credentialData['key'] = $key;
                        $defaultArr['default'] = $credential;
                    } else {
                        $credentialData['key'] = $credential;
                    }
                    PaymentCredential::updateOrCreate($credentialData, $defaultArr);
                }
            }
            $this->command->info("{$configName} -> seeded");
        }
    }
}
