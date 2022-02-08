<?php

namespace App\Modules\Payment\Methods\Online;

use App\Modules\Order\Entities\Transaction;
use Illuminate\Http\Request;

class Paypal extends OnlinePaymentContract
{
    private $SecretApiKey;
    private $merchantId;
    public $url;
    public function __construct()
    {
        $credentials = $this->getCredentials('paypal');

        dd($this);
    }
    public function getPayPage($request, $total, $user = null)
    {
        $description = implode(' - ', $user->cart->items->pluck('name')->toArray());
        $transaction = $this->createTransaction(null, $total);
        $options = [
            'amount' => $total,
            'currency' => "SAR",
            'threeDSecure' => 'true',
            'description' => $description,
            'customer' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => [
                    'country_code' => "sa",
                    'number' => $user->phone
                ]
            ],
            'receipt' => [
                'email' => 'false',
                'sms' => 'false'
            ],
            'merchant' => [
                'id' => $this->merchantId
            ],
            'reference' => [
                'transaction' => $transaction->id,
            ],
            'source' => [
                'id' => "src_all",
            ],
            'post' => [
                'url' => null
            ],
            'redirect' => [
                'url' => url('api/orders/pay_call_back/2')
            ]
        ];
        $response = $this->requestApi("charges", 'post',  $options);
        $this->logResponse($response, $options);
        $transaction->update(['payment_reference' => $response->id]);
        return $response->transaction->url;
    }
}
