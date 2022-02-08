<?php

namespace App\Modules\Coupon\Http\Controllers\Admin;


use App\Exports\CouponExport;
use App\Modules\Coupon\Entities\Coupon;
use App\Modules\Coupon\Http\Requests\CouponRequest;
use App\Modules\Coupon\Repositories\CouponRepository;
use App\Modules\Coupon\Transformers\CouponResource;
use App\Modules\Product\Entities\Lists;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;


class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $couponRepository;

    public function __construct(CouponRepository $couponRepository)
    {
        $this->couponRepository = $couponRepository;
        $this->middleware('permission:coupon-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:coupon-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:coupon-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:coupon-delete', ['only' => ['destroy']]);
        $this->middleware('permission:coupon-export-excel', ['only' => ['exportExcel']]);
        $this->middleware('permission:coupon-import-excel', ['only' => ['importExcel']]);
    }

    public function index()
    {
        $coupons = $this->couponRepository->all();
        return $this->apiResponse(CouponResource::collection($coupons));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CouponRequest $request)
    {
        $coupon = $this->couponRepository->storeCoupon($request->validated());
        return $this->apiResponse(new CouponResource($coupon));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $coupon = Coupon::find($id);
        return $this->apiResponse(new CouponResource($coupon));
    }
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CouponRequest $request, $id)
    {
        $coupon = Coupon::find($id);
        $coupon->update($request->validated());
        return $this->apiResponse(new CouponResource($coupon));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();
        return $this->apiResponse(CouponResource::collection(Coupon::all()));
    }

    public function exportExcel()
    {
        $date = date('Y-m-d-H-i-s');
        Excel::store(new CouponExport(), "excels/coupons/coupons-{$date}.xlsx", 'public');
        return $this->apiResponse(['file_url' => asset("storage/excels/coupons/coupons-{$date}.xlsx")]);
    }

    public function importExcel()
    {

    }
}
