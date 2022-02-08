<?php

namespace App\Modules\Payment\Methods\Telr;

use App\Modules\Order\Entities\Order;
use App\Modules\Order\Entities\Transaction;
use App\Modules\Payment\Events\TelrFailedTransactionEvent;
use App\Modules\Payment\Events\TelrRecieveTransactionResponseEvent;
use App\Modules\Payment\Events\TelrSuccessTransactionEvent;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class TelrManager
{
    /**
     * Prepare create request
     *
     * @param $orderId
     * @param $amount
     * @param $description
     * @param array $billingParams
     */
    public function prepareCreateRequest($orderId, $amount, $description, array $billingParams = [])
    {
        //dd($orderId, $amount);
        $createTelrRequest = (new CreateTelrRequest($orderId, $amount))->setDesc($description);
        //Set Telr request lang
        $createTelrRequest->setLangCode(app()->getLocale());
        // Associate billing params to fields
        foreach ($billingParams as $key => $value) {
            $methodName = ('setBilling' . Str::studly($key));
            if (method_exists($createTelrRequest, $methodName)) {
                $createTelrRequest->$methodName($value);
            }
        }
        //  dd($createTelrRequest);
        return $createTelrRequest;
    }

    /**
     * Initiate create request on telr
     *
     * @param $orderId
     * @param $amount
     * @param $description
     * @param array $billingParams
     * @return TelrURL
     * @throws \Exception
     */
    public function pay($orderId, $amount, $description, array $billingParams = [])
    {
        $createRequest = $this->prepareCreateRequest($orderId, $amount, $description, $billingParams);
        // dd($createRequest->toArray());
        $result = $this->callTelrServer($createRequest->getEndPointURL(), $createRequest->toArray());
        // Validate if response has error messages
        if (isset($result->error)) {
            throw new \Exception($result->error->message . '. Note: ' . $result->error->message);
        }
        // Dispatch event
        $createRequest = $createRequest->toArray();
//        event(new TelrCreateRequestEvent($createRequest, $result));
//        dd($createRequest['ivp_cart']);
        $order = Order::findOrFail($orderId);
        $transaction = Transaction::create([
            'order_details' => [$order],
            'payment_reference' => $createRequest['ivp_cart'],
            'total_amount' => $amount,
            'transaction_response' => $createRequest,
            'transaction_status' => '1',
            'status_code' => '000',
            'card_info' => "",
            'user_id' => Auth::id()
        ]);
//        dd($transaction);
        return new TelrURL($result->order->url);
    }

    /**
     * Fetch the transaction result
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\ModelNotFoundException|Transaction
     * @throws \Exception
     */
    public function handleTransactionResponse(Request $request)
    {
        $transaction = TelrTransaction::findOrFail($request->cart_id);
        $trxResultRequest = new TelrTransactionResultRequest($transaction);

        $result = $this->callTelrServer($trxResultRequest->getEndPointURL(), $trxResultRequest->toArray());

        // Validate if response has error messages
        if (isset($result->error)) {
            throw new \Exception($result->error->message . '. Note: ' . $result->error->note);
        }

        // Dispatch event for after receiving telr response
        event(new TelrRecieveTransactionResponseEvent($transaction, $result));

        // Is success transaction
        if (3 === $result->order->status->code && 'paid' === strtolower($result->order->status->text)) {
            // Mark the transaction as approved
            $transaction->approve();

            // Dispatch success transaction
            event(new TelrSuccessTransactionEvent($transaction, $result));

            return $transaction;
        }

        // Mark the transaction as failed
        $transaction->failed();

        // Dispatch failed transaction
        event(new TelrFailedTransactionEvent($transaction, $result));

        return $transaction;
    }

    /**
     * Call the telr server
     *
     * @param $endPoint
     * @param $formParams
     * @return mixed
     */
    protected function callTelrServer($endPoint, $formParams)
    {
        $client = new Client();
        $result = $client->post($endPoint, ['form_params' => $formParams]);
        // Validate if response is equal 200
        if (200 != $result->getStatusCode()) {
            throw new ClientException('The response is ' . $result->getStatusCode());
        }
        // Convert json response into object
        return \GuzzleHttp\json_decode($result->getBody()->getContents());
    }
}
