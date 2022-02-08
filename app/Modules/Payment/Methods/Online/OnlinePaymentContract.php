<?php

namespace App\Modules\Payment\Methods\Online;

use App\Modules\Order\Entities\Transaction;
use App\Modules\Payment\Entities\PaymentMethod;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class OnlinePaymentContract
{
    abstract public function getPayPage($request, $total);

    /**
     * @param $paymentReference
     * @param $total
     * @return mixed
     */
    public function createTransaction($paymentReference, $total)
    {
        $request = request()->all();
        $request['total'] = $total;
        $transaction = Transaction::create([
            'order_details' => $request ,
            'payment_reference' => $paymentReference,
            'total_amount' => $total,
            'user_id' => Auth::id()
        ]);
        return $transaction;
    }


    public function logResponse($finalOutput, $options, $type = 'create-charge', $request = null)
    {
        Log::channel("payment_methods")->info(
            "{$type} \n" .
            json_encode([
                'our_request' => $request ?: request()->all(),
                'data' => [
                    'response' => $finalOutput,
                    'request' => $options,
                ],
            ])
        );
    }

    public function redirectUrl($path)
    {
        $root = config('app.website_url');

        return $root . '/' . trim($path, '/');
    }

    public function requestApi($endPoint, $method = 'get', $options = [])
    {

        $host = "{$this->url}/{$endPoint}";

        $client = new Client();
        $headers = [
            'http_errors' => false,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->token}"
            ]
        ];
        $options = array_merge(['json' => $options], $headers);
        try {
            $apiRequest = $client->{$method}($host, $options);
            $finalOutput = json_decode($apiRequest->getBody());
            $payment = Str::between($this->url, '//', '.');
            $this->logResponse($finalOutput, $options, "{$payment} - " . debug_backtrace()[1]['function']);
            return $finalOutput;
        } catch (\Exception $ex) {
            Log::channel('payment_methods')->info("Payment-exception\n" . json_encode(['status_code' => $ex->getCode(), 'message' => $ex->getMessage()]));
            return (object)['status_code' => 500];
        }

    }

    public function getCredentials($method)
    {
        $credentials = PaymentMethod::where('name->en', $method)->first()->credentials
            ->mapWithKeys(function ($cre) {
                $val = $cre->value ?? $cre->default;
                if (in_array($val, ['true', 'false'])) {
                    $val = $val === 'true' ? true : false;
                }
                if ($val === '0') {
                    $val = 0;
                }
                return [$cre->key => $val];
            });
        return $credentials->toArray();
    }
}
