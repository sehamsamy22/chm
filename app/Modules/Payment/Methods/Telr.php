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
        $addesses=Auth::user()->addresses;
        //  dd(Auth::user()->phone);
        $billingParams = [
            'first_name' => Auth::user()->name,
            'sur_name' => 'chm',
            'address_1' => $addesses[0]->address,
            'address_2' =>$addesses[1]->address,
            'city' => $order->address->area->city->name??'',
            'country' => 'SA',
            'email' => Auth::user()->email,
           'phone'=>Auth::user()->phone
        ];
        $telrRequest = $telrManager->prepareCreateRequest($order->id, $order->total, 'order desc',  $billingParams);
   //  dd($telrRequest);
        return $telrManager->pay($order->id, $order->total, 'order desc',   $billingParams)->redirect();
    }

    public function payCallBack($request)
    {
        // dd($request);
        $payment_id = $request['order_id'];

    }

}
