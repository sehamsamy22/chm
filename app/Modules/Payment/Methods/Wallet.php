<?php

namespace App\Modules\Payment\Methods;

use App\Modules\Order\Transformers\OrderResource;
use App\Modules\Payment\Entities\PaymentMethod;
use Facades\App\Modules\Order\Repositories\OrderRepository;
use Illuminate\Support\Facades\Auth;

class Wallet extends PaymentContract
{
    public function checkAvailability()
    {
        return PaymentMethod::where('name->en', 'wallet')->active()->first();
    }

    public function initiate($request, $user)
    {

        if (!$this->checkAvailability()) return $this->response($this->errorMessage, null, 422, false);
        $totalAmount = OrderRepository::calculateOrderFullTotal($request, $user);
        // TODO check if the wallet has order total amount
        $wallet = $user->wallet;
        if (!$wallet) return $this->response($this->errorMessage, null, 422, false);;
        if ($wallet->amount < $totalAmount) return $this->response($this->errorMessage, null, 422, false);;
        $request['total'] = OrderRepository::calculateOrderFullTotal($request, $user);
        $order = OrderRepository::createOrder($request, Auth::user());
        // TODO update amount in wallet table, create wallet log
        $user->wallet()->update(['amount' => $wallet->amount - $totalAmount]);
        $wallet->logs()->create(['order_id' => $order->id, 'amount' => $totalAmount, 'status' => 0]);
        return $this->response("Success", new OrderResource($order), 200);
    }
}
