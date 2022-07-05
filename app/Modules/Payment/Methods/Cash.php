<?php

namespace App\Modules\Payment\Methods;

use App\Modules\Order\Transformers\OrderResource;
use App\Modules\Payment\Entities\PaymentMethod;
use App\Modules\Shipping\Methods\ShippingMethods;
use Facades\App\Modules\Order\Repositories\OrderRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class Cash extends PaymentContract
{
    public function checkAvailability()
    {
        return PaymentMethod::where('name->en', 'cash')->active()->first();
    }

    public function initiate($request, $user)
    {
        $url = Config::get('app.front_url');
        if (!$this->checkAvailability()) return $this->response($this->errorMessage, null, 422, false);
        $request['total'] = OrderRepository::calculateOrderFullTotal($request, $user);
        $order = OrderRepository::createOrder($request, Auth::user());

        if (isset($request['shipping_method_id'])) {
            if ($shippingtInstance = ShippingMethods::shippingClass($request['shipping_method_id'])) {
                $shipping = (new $shippingtInstance)->CreatePickup([$order->id]);
            }
        }
        return $this->response("Success",['pay_url' => URL::to($url."/payment?status=true"),'order_details'=>new OrderResource($order)], 200);
    }
}
