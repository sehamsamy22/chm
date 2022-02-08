<?php

namespace App\Modules\Payment\Methods;

use App\Models\Admin;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Notifications\NewOrder;
use App\Modules\Payment\Entities\PaymentMethod;
use Facades\App\Modules\Order\Repositories\OrderRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use PHPUnit\Exception;
use URL;

class PayPal extends PaymentContract
{
    private $accounts = [
        'client_id' => 'AeykFnKDpmvdholjiGTAflF-To_RafuxNP_vGw4NEXeOsyPliVdhkMbVLnhPMlfTwUOOUOTNrzo-IaQX',
        'secret_client' => 'EKOfLFj44w8EeMUCxBE26l4D24k6Bvwaulsfg-pCdL8RHroA57r3dX1yl6zUsYDfH_vfSsd1opDHYJan',
    ];
    private $settings = ['mode' => 'sandbox', 'http.ConnectionTimeOut' => 30, 'log.logEnable' => true, 'logFileName' => '/logs/paypal.log'];

    public function __construct()
    {
        /** PayPal api context **/
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->accounts['client_id'],
                $this->accounts['secret_client']
            )
        );
        $this->apiContext->setConfig($this->settings);
    }

    public function checkAvailability()
    {
        return PaymentMethod::where('name->en', 'tap')->active()->first();
    }

    /**
     * Display a listing of the resource.
     * @return array
     */

    public function initiate($request, $user)
    {

        if (!$this->checkAvailability()) {
            return $this->response($this->errorMessage, null, 400, false);
        }
        $totalAmount = OrderRepository::calculateOrderFullTotal($request, $user);
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

//        $item_1 = new Item();
//
//        $item_1->setName('Product 1')
//            ->setCurrency('USD')
//            ->setQuantity(1)
//            ->setPrice(2);
//
//        $item_list = new ItemList();
//        $item_list->setItems(array($item_1));
        $string = json_encode($request);
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($totalAmount);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
//            ->setItemList($item_list)
            ->setDescription("uio")
            ->setCustom($string);
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::to('api/payments/status/' . PaymentMethods::paymentId('PAYPAL')))
            ->setCancelUrl(URL::to('api/payments/status'));;
        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));
        try {
            $payment->create($this->apiContext);
            $payment->fromArray($request);
        } catch (PayPalConnectionException $ex) {
            echo $ex->getCode(); // Prints the Error Code
            echo $ex->getData(); // Prints the detailed error message
            dd($ex);
        } catch (\Exception $ex) {
            die($ex);
        }
        $redirect_url = $payment->getApprovalLink();
        if ($redirect_url != null)
            return $this->response('يرجي اكمال عملية الدفع', ['pay_url' => $redirect_url], 202);
    } //endif


    public function payCallBack($request)
    {
        $paymentId = $request['paymentId'];
        $payerId = $request['PayerID'];
        $token = $request['token'];
        if ($paymentId != '' && $payerId != '' && $token != '') {
            $payment = Payment::get($paymentId, $this->apiContext);
            $items = $payment->transactions[0]->item_list->items;
            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);
            $transaction = new Transaction();
            $amount = new Amount();
            $details = new Details();
            $total = $payment->transactions[0]->amount->total;
            $details->setTax(0)->setSubtotal($total);
            $amount->setCurrency('USD')->setTotal($total)->setDetails($details);
            $transaction->setAmount($amount);
            $execution->addTransaction($transaction);
            $decodedCustom = $payment->getTransactions()[0]->custom;
            $custom = json_decode($decodedCustom);

            try {
                // Execute payment
                $result = $payment->execute($execution, $this->apiContext);
            } catch (PayPalConnectionException $ex) {
                echo $ex->getCode();
                echo $ex->getData();
                die($ex);
            } catch (Exception $ex) {
                die($ex);
            }

            $orderDetails = ["payment_method_id" => $custom->payment_method_id ??'' , "address_id" =>$custom->address_id??'' ,
                "store_id" => $custom->store_id ??'', "user_id" => $custom->user_id??'', "total" => $custom->total ??$total, "prom_code" => $custom->prom_code??null];
//          dd($orderDetails);
            if($orderDetails['prom_code']==null){
                unset($orderDetails['prom_code']);
            }

            $transaction = \App\Modules\Order\Entities\Transaction::create([
                'order_details' => $orderDetails,
                'payment_reference' => $paymentId,
                'total_amount' => $total,
                'transaction_response' => json_decode($result),
                'transaction_status' => "1",
                'user_id' => $custom->user_id
            ]);
           return $transaction;
        }
    }


}
