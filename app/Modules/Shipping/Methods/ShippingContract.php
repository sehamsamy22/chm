<?php


namespace App\Modules\Shipping\Methods;


use App\Modules\Shipping\Entities\OrderPickup;
use App\Modules\Shipping\Entities\Pickup;
use App\Modules\Shipping\Entities\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class ShippingContract
{


    public function logResponse($url, $finalOutput, $options, $method)
    {
        Log::channel("shipping")->info(
            "{$method}: \n" .
            json_encode([
                'requestData' => request()->all(),
                'paymentData' => [
                    'url' => $url,
                    'response' => $finalOutput,
                    'request' => $options,
                ],
            ])
        );
    }

    public function createPickupRow(Request $request)
    {

        $pickUpData = [
            "method_id" => $request->shipping_method_id,
            "notes" => $request->shipping_notes??null,
            "pickup_time" => now(),
            "status" => 1,
            "shipping_id" => $request->shipping_id,
            "shipping_guid" => $request->shipping_guid ?? null,
        ];
        return Pickup::create($pickUpData);
    }

    public function createOrderPickup($pickup)
    {
        $request = request();
        $orderPickup = OrderPickup::create([
            "pickup_id" => $pickup->id,
            "order_id" => $request->order_id,
            "shipping_id" => $request->shipping_id ?? null,
            "foreign_hawb" => $request->foreign_hawb ?? null,
            "shipment_url" => $request->shipment_url,
        ]);
        return $orderPickup;
    }

    public function getCredentials($method)
    {
        $credentials = ShippingMethod::where('name->en', $method)->first()->credentials
            ->mapWithKeys(function($cre) {
                $val = $cre->value ?? $cre->default;
                if(in_array($val, ['true', 'false'])) {
                    $val = $val === 'true' ? true : false;
                }
                if($val === '0') {
                    $val = 0;
                }
                return [$cre->name => $val];
            });
        return $credentials->toArray();
    }
}
