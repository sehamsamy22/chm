<?php

namespace App\Modules\Order\Http\Controllers\Admin;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Notifications\UpdateOrderStatus;
use App\Modules\Order\Repositories\OrderRepository;
use App\Modules\Order\Transformers\OrderResource;
use App\Modules\Shipping\Methods\ShippingMethods;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private $orderRepository;

    // public function __construct(OrderRepository $orderRepository)
    // {
    //     $this->orderRepository = $orderRepository;
    //     $this->middleware('permission:order-list', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:order-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:order-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:order-delete', ['only' => ['destroy']]);
    //     $this->middleware('permission:order-export-excel', ['only' => ['exportExcel']]);
    //     $this->middleware('permission:order-import-excel', ['only' => ['importExcel']]);
    // }

      public function index(Request $request)
    {
        $orders = Order::all();
        $total = $orders->count();
        return $this->apiResponse([
            "orders" => OrderResource::collection($orders->paginate($request['pageLimit'])),
            'total' => $total,
            'page'=>$request['page'],
            'pageLimit'=>$request['pageLimit'],
            'lastPages' => isset($request['pageLimit']) ? ceil((!empty($orders) ? $total : 0) / $request['pageLimit']) : 0]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $order = Order::find($id);
        return $this->apiResponse(new OrderResource($order));
    }

    public function filtration(Request $request)
    {
        $request->validate([
            "city_id" => "sometimes|exists:cities,id",
            "area_id" => "sometimes|exists:areas,id",
        ]);
        $orders = $this->orderRepository->filterOrders($request->all());

        $total = $orders->count();
        if ($orders instanceof LengthAwarePaginater) $total = $orders->total();
        return $this->apiResponse([
            'orders' => OrderResource::collection($orders),
            'orders_total' => $total,
        ]);
    }

    public function exportExcel()
    {
        $date = date('Y-m-d-H-i-s');
        Excel::store(new OrdersExport(), "excels/orders/orders-{$date}.xlsx", 'public');
        return $this->apiResponse(['file_url' => asset("storage/excels/orders/orders-{$date}.xlsx")]);
    }

    public function importExcel()
    {

    }

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            "status" => "required",
        ]);
        $order = Order::find($id);
        $this->orderRepository->changeOrderStatus($order, $request['status']);
        $user = User::find($order->user_id);
        Notification::send($user, new UpdateOrderStatus($order));
        return $this->apiResponse(new OrderResource($order));
    }

    public function createShipment(Request $request)
    {
        $request->validate([
            "shipping_method_id" => "required|exists:shipping_methods,id",
            "warehouse_id" => "required|exists:warehouses,id",
        ]);
        $shippingtInstance = ShippingMethods::shippingClass($request['shipping_method_id']);
        $shipping = (new $shippingtInstance)->CreatePickup($request);
        return $this->apiResponse($shipping);

    }
}
