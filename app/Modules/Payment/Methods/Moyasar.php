<?php

namespace App\Modules\Payment\Methods;

use App\Modules\Order\Transformers\OrderResource;
use App\Modules\Payment\Entities\PaymentMethod;
use App\Modules\Shipping\Methods\ShippingMethods;
use Facades\App\Modules\Order\Repositories\OrderRepository;
use Illuminate\Support\Facades\Auth;
use Moyasar\Providers\PaymentService;

class Moyasar extends PaymentContract
{
    public function checkAvailability()
    {
        return PaymentMethod::where('name->en', 'cash')->active()->first();
    }

    public function initiate($request, $user)
    {
        $url = "https://api.moyasar.com/v1/payments";
        $totalAmount = OrderRepository::calculateOrderFullTotal($request, $user);
        $request['user_id']= Auth::user()->id;
        $request['total']=$totalAmount;
        return $this->response('يرجي اكمال عملية الدفع', ['pay_url' => $url,'order_details'=>$request], 202);
    }


}
