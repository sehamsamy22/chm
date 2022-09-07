<?php

namespace App\Modules\Payment\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Order\Entities\Transaction;
use App\Modules\Payment\Entities\PaymentMethod;
use App\Modules\Payment\Transformers\PaymentResource;
use App\Modules\Payment\Transformers\WalletLogResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $payments = PaymentMethod::with(['credentials'])->get();
        return $this->apiResponse(PaymentResource::collection($payments));
    }

    public function myWallet()
    {
        $myWallet = Auth::guard('api')->user()->wallet;
        return $this->apiResponse(['myWallet' => $myWallet->amount, 'logs' => WalletLogResource::collection($myWallet->logs)]);
    }

    public function declined(Request $request)
    {
        $transaction = Transaction::where('payment_reference', $request['cart_id'])->first();
        $transaction->update(['transaction_status' => '0']);
        return $this->apiResponse("your transaction is declined");
    }
    public function cancel(Request $request)
    {
        $transaction = Transaction::where('payment_reference', $request['cart_id'])->first();
        $transaction->update(['transaction_status' => '0']);
        return $this->apiResponse("your transaction is cancel");
    }
    public function success(Request $request)
    {
        $transaction = Transaction::where('payment_reference', $request['cart_id'])->first();
        $transaction->update(['transaction_status' => '1']);
        return $this->apiResponse("your transaction is successfully");
    }
}
