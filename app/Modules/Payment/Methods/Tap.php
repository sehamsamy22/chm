<?php

namespace App\Modules\Payment\Methods;

use App\Modules\Order\Entities\Transaction;
use App\Modules\Payment\Entities\PaymentMethod;
use App\Modules\Payment\Methods\Online\Tap as OnlineTap;
use Facades\App\Modules\Order\Repositories\OrderRepository;

class Tap extends PaymentContract
{
    public function checkAvailability()
    {
        return PaymentMethod::where('name->en', 'tap')->active()->first();
    }

    public function initiate($request, $user)
    {
        if (!$this->checkAvailability()) {
            return $this->response($this->errorMessage, null, 400, false);
        }
       $totalAmount = OrderRepository::calculateOrderFullTotal($request, $user);
//        dd($request,$totalAmount,$user);
        $url = $this->online()->getPayPage($request, $totalAmount, $user);
        return $this->response('يرجي اكمال عملية الدفع', ['pay_url' => $url], 202);
    }

    public function payCallBack($request)
    {
        $payment_id = $request['tap_id'];
        try {
            $response = $this->online()->requestApi("charges/{$payment_id}");
            $this->online()->logResponse($response, ['payment_id' => $payment_id], 'pay-callback');
        } catch (\Exception $e) {
            return false;
        }
        $transaction = Transaction::where('payment_reference', $payment_id)->first();
        if (!$transaction)
            return false;
        $paymentCode = optional(optional(optional($response)->response))->code;
        $transaction->update([
            'card_info' => optional($response)->card ? "{$response->card->first_six} - {$response->card->last_four} -- {$response->card->brand}" : "",
            'transaction_response' => $response,
            'response_code' => $paymentCode,
            'transaction_status' => $paymentCode === "000"
        ]);
        return $transaction;
    }

    public function online()
    {
        return new OnlineTap;
    }
}
