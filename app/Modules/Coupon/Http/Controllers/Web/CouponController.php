<?php

namespace App\Modules\Coupon\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Coupon\Entities\Coupon;
use App\Modules\Coupon\Repositories\CouponRepository;
use App\Modules\Coupon\Transformers\CouponResource;
use App\Modules\Order\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    private $couponRepository;
    private $orderRepository;

    public function __construct(CouponRepository $couponRepository, OrderRepository $orderRepository)
    {
        $this->couponRepository = $couponRepository;
        $this->orderRepository = $orderRepository;
    }

    public function checkCoupon(Request $request)
    {
        $user = Auth::user();
        $coupon = Coupon::where('prom_code', $request['prom_code'])->first();
        if (!$coupon) return $this->errorResponse('coupon doesnt exists' ,[], 422);
        $validations = $this->couponRepository->validations($coupon, $user)->validate()->first();
        if ($validations) {
            return $this->errorResponse($validations->getMessage(), [], 422);
        }
        $couponProducts = $this->couponRepository->checkCustomizationsCoupon($user, $coupon);
        $discount = $this->orderRepository->calculateCouponDiscount($coupon, $user,$couponProducts);

        return $this->apiResponse([
            'message' => "coupon ready to used.",
            'status' => true,
            'discount' => $discount,
            'coupon' => new CouponResource($coupon),
            'couponProducts' => $couponProducts
        ]);
    }
}
