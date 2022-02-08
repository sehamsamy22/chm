<?php

namespace App\Modules\Payment\Methods;

use App\Modules\Payment\Entities\PaymentMethod;
use App\Modules\Payment\Methods\Telr\TelrManager;
use Facades\App\Modules\Order\Repositories\OrderRepository;
use App\Modules\Payment\Methods\Online\Tap as OnlineTap;

use Illuminate\Support\Facades\Auth;

class Telr extends PaymentContract
{
    public function checkAvailability()
    {
        return PaymentMethod::where('name->en', 'telr')->active()->first();
    }

    public function initiate($request, $user)
    {
        if (!$this->checkAvailability()) return $this->response($this->errorMessage, null, 422, false);
        $request['total'] = OrderRepository::calculateOrderFullTotal($request, $user);
//        dd($request, Auth::user());
        $order = OrderRepository::createOrder($request, Auth::user());
        $telrManager = new TelrManager();
        // $billingParams = [
        //     'first_name' => 'chm',
        //     'sur_name' => 'market',
        //     'address_1' => 'Gnaklis',
        //     'address_2' => 'Gnaklis 2',
        //     'city' => 'Alexandria',
        //     'region' => 'San Stefano',
        //     'zip' => '11231',
        //     'country' => 'EG',
        //     'email' => 'sehamsamy755@gmail.com',
        // ];
        $telrRequest = $telrManager->prepareCreateRequest($order->id, $order->total, 'order desc', []);
   //  dd($telrRequest);
        return $telrManager->pay($order->id, $order->total, 'order desc', [])->redirect();
    }

    public function payCallBack($request)
    {
        $payment_id = $request['cart_id'];

    }

}
