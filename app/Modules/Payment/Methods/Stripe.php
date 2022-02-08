<?php

namespace App\Modules\Payment\Methods;

use App\Models\Admin;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Entities\Transaction;
use App\Modules\Order\Notifications\NewOrder;
use App\Modules\Order\Transformers\OrderResource;
use App\Modules\Payment\Entities\PaymentMethod;
use App\Modules\Shipping\Methods\ShippingMethods;
use Facades\App\Modules\Order\Repositories\OrderRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Moyasar\Providers\PaymentService;
use Stripe\Error\Card;
use Validator;

class Stripe extends PaymentContract
{
    public function checkAvailability()
    {
        return PaymentMethod::where('name->en', 'cash')->active()->first();
    }

    public function initiate($request, $user)
    {
        $input = $request;
        $validator = Validator::make($request, [
            'number' => 'required',
            'exp_month' => 'required',
            'exp_year' => 'required',
            'cvc' => 'required',
            //'amount' => 'required',
        ]);
        if ($validator->passes()) {
            $stripe = \Cartalyst\Stripe\Laravel\Facades\Stripe::setApiKey(env('STRIPE_SECRET'));
            try {
                $token = $stripe->tokens()->create([
                    'card' => [
                        'number' => $request['number'],
                        'exp_month' => $request['exp_month'],
                        'exp_year' => $request['exp_year'],
                        'cvc' => $request['cvc'],
                    ],
                ]);
                if (!isset($token['id'])) {
                    return response()->json('error card');
                }
                $totalAmount = OrderRepository::calculateOrderFullTotal($request, $user);
                $charge = $stripe->charges()->create([
                    'card' => $token['id'],
                    'currency' => 'USD',
                    'amount' => $totalAmount,
                    'description' => 'wallet',
                ]);
                if($charge['status'] == 'succeeded') {
                   unset($request['number']) ;
                    unset($request['exp_month']) ;
                    unset($request['exp_year']) ;
                    unset($request['cvc']) ;
                    $request['total']=$totalAmount;
                    $transaction = Transaction::create([
                        'order_details' =>  array_merge($request,$charge['payment_method_details']),
                        'payment_reference' =>$charge['id'],
                        'total_amount' => $totalAmount,
                        'transaction_response' => $charge,
                        'transaction_status' => '1',
                        'status_code' => '000',
                        'card_info' => $charge['payment_method_details']['type']=='card'?$charge['payment_method_details']['card']['brand']."-".$charge['payment_method_details']['card']['last4']:"",
                        'user_id' => Auth::id()
                    ]);
                    $order = OrderRepository::createOrder($transaction->order_details, $transaction->user, Order::PAID,$transaction->id);
                    Notification::send(Admin::role('GeneralManager')->get(), new NewOrder($order));
                    $url=config('app.front_url') . "/payment?status=true&order={$order->unique_id}";
                    return $this->response('تم  اكمال عملية الدفع', ['pay_url' => $url], 202);
                } else {
                    \Session::put('error','Money not add in wallet!!');
                    return redirect()->route('addmoney.paymentstripe');
                }
            } catch (Exception $e) {
                \Session::put('error',$e->getMessage());
                return response()->json('error card');
            } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
                \Session::put('error',$e->getMessage());
                return response()->json('error card');
            } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                \Session::put('error',$e->getMessage());
                return response()->json('error card');
            }
        }


    }
}
