<?php

namespace App\Modules\Order\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Modules\Coupon\Entities\Coupon;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Entities\PickupTime;
use App\Modules\Order\Entities\Transaction;
use App\Modules\Order\Http\Requests\OrderRequest;
use App\Modules\Order\Notifications\NewOrder;
use App\Modules\Order\Repositories\OrderRepository;
use App\Modules\Order\Transformers\OrderResource;
use App\Modules\Order\Transformers\PickupTimeResource;
use App\Modules\Payment\Methods\PaymentMethods;
use Exception;
use App\Modules\Coupon\Repositories\CouponRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Moyasar\Providers\PaymentService;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function ordersFilter(Request $request)
    {
        $orders = Auth::user()->orders->where('status', $request['status']);
        return $this->apiResponse(OrderResource::collection($orders));
    }

    public function getDeliveryFees($id)
    {
        $fees = $this->orderRepository->getShippingPrice($id);
        return $this->apiResponse(['delivery_fees' => $fees]);
    }

    public function show(Order $order)
    {
        $userOrdersIds = Auth::user()->orders->pluck('id')->toArray();
        if (!in_array($order->id, $userOrdersIds)) return $this->errorResponse('order not found');
        return $this->apiResponse(new OrderResource($order));
    }

    public function items($id)
    {
        $order = $this->orderRepository->items($id);
        return $this->apiResponse(new OrderResource($order));
    }

    public function store(OrderRequest $request)
    {
        try {
            $request = $request->all();
            //check order validations
            $user = Auth::user();
            $orderValidation = $this->orderRepository->validations($request, $user);
            if (!$orderValidation->valid()) {
                $errors = $orderValidation->errors();
                return $this->errorResponse($errors->first()->message, $errors, 422);
            }
            // check coupon validations
            if ($couponCode = $request['prom_code'] ?? null) {
                if ($coupon = Coupon::where('prom_code', $couponCode)->with(['customizations'])->first()) {
                    // check coupon validations
                    $couponValidation = CouponRepository::validations($coupon, $user);
                    if (!$couponValidation->valid()) {
                        $errors = $couponValidation->errors();
                        return $this->errorResponse($errors->first()->message, $errors, 422);
                    }
                    $request['coupon_discount'] = $this->orderRepository->calculateCustomizationsDiscount($coupon, $user);
                }
            }
            $payment = ['success' => false];
            if ($paymentInstance = PaymentMethods::paymentClass($request['payment_method_id'])) {
                $payment = (new $paymentInstance)->initiate($request, $user);
            }
            if (!$payment['success']) return $this->errorResponse($payment['message'], $payment['data'], $payment['status_code']);
            return $this->apiResponse($payment['data'], $payment['status_code']);
        } catch (Exception $e) {
            dd($e);
            return $this->errorResponse('There is error, PLZ contact with Tech Team');
        }
    }

    public function checkOrderCoupon($couponCode, $user)
    {
        $coupon = Coupon::where('prom_code', $couponCode)->with(['customizations'])->first();
        if (!$coupon) return false;
        $couponValidation = CouponRepository::validations($coupon, $user);
        if (!$couponValidation->valid()) {
            $errors = $couponValidation->errors();
            return $this->errorResponse($errors->first()->message, $errors, 422);
        }
    }

    public function callback(Request $request, $paymentMethodId = null)
    {
        $transactionExist = Transaction::where('payment_reference', $request->tap_id)->first();
        $request->headers->set("store_id", $transactionExist->order_details["store_id"]);
        if ($transactionExist->order != null) {
            return redirect(config('app.front_url') . "/payment?status=true&order={$transactionExist->order->unique_id}");
        } else {
            $paymentInstance = PaymentMethods::paymentClass($paymentMethodId);
            try {
                $transaction = (new $paymentInstance)->payCallBack($request->all());

            } catch (\Throwable $e) {
                return redirect(config('app.front_url') . "/payment?status=false");
            }
            if ($paymentInstance != 'App\Modules\Payment\Methods\PayPal') {
                (new $paymentInstance)->online()->logResponse($request->all(), [], "{$paymentMethodId} - callback");
            }
            if (!optional($transaction)->transaction_status) {
                $transaction->update([
                    'status' => 'cancelled'
                ]);
                return redirect(config('app.front_url') . "/payment?status=false");
            }
            $transaction->update([
                'status' => 'paid'
            ]);

            $order = $this->orderRepository->createOrder($transaction->order_details, $transaction->user, Order::PAID, $transaction->id);

//            Notification::send(Admin::role('GeneralManager')->get(), new NewOrder($order));
            return redirect(config('app.front_url') . "/payment?status=true&order={$order->unique_id}");
        }
    }
       public function telrDeclinedCallback(Request $request)
    {
        $transactionExist = Transaction::where('payment_reference', $request->cart_id)->first();
        $transactionExist->update([
            'status' => 'cancelled'
        ]);

        return redirect(config('app.front_url') . "/payment?status=false");
    }

    public function telrSuccessCallback(Request $request)
    {
        $transactionExist = Transaction::where('payment_reference', $request->cart_id)->first();
        $transactionExist->update([
            'status' => 'paid'
        ]);
        $data=$transactionExist->order_details;
        $data['payment_method_id']=PaymentMethods::paymentId('TELR');
        $order = $this->orderRepository->createOrder($transactionExist->order_details, $transactionExist->user, Order::PAID, $transactionExist->id);

//        Notification::send(Admin::role('GeneralManager')->get(), new NewOrder($order));
        return redirect(config('app.front_url') . "/payment?status=true&order={$order->unique_id}");
    }

    public function moyasar_callback(Request $request)
    {
        $id = $request['id'];
        $token = base64_encode(env("MOYASAR_SECRET_KEY") . ":");
        $payment = Http::baseUrl('https://api.moyasar.com/v1/')->withHeaders([
            'Authorization' => 'Basic ' . $token,
        ])->get('payments/' . $id)->json();
        $transactionExist = Transaction::where('payment_reference', $payment['id'])->first();
        if (isset($transactionExist)) {
            $request->headers->set("store_id", $transactionExist->order_details["store_id"]);
            return redirect(config('app.front_url') . "/payment?status=true&order={$transactionExist->order->unique_id}");
        } else {
            $transaction = Transaction::create([
                'order_details' => $payment['metadata'],
                'payment_reference' => $payment['id'],
                'transaction_response' => $payment,
                'total_amount' => $payment['metadata']['total'],
                'user_id' => $payment['metadata']['user_id']
            ]);
            $order = $this->orderRepository->createOrder($transaction->order_details, $transaction->user, Order::PAID, $transaction->id);
//            Notification::send(Admin::role('GeneralManager')->get(), new NewOrder($order));
            return redirect(config('app.front_url') . "/payment?status=true&order={$order->unique_id}");
        }
    }

    public function getPickupTimes($day)
    {
        $pickupTimes = PickupTime::where('day', $day)->get();
        return $this->apiResponse((PickupTimeResource::collection($pickupTimes)));
    }
}
