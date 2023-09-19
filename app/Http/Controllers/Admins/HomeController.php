<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminIndexResource;
use App\Models\User;
use App\Modules\Ad\Entities\Ad;
use App\Modules\Category\Entities\Category;
use App\Modules\Order\Entities\Order;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Transformers\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::count();
        $customers = User::count();
        $products = Product::count();
        $ads = Ad::count();

        $orders = Order::count();
          dd("erwer",$request->header(), $request->header('store_id'));  

        $lastOrders = Order::where('store_id','=', app('Illuminate\Http\Request')->header('store_id')) ->orderBy('created_at', 'desc')
            ->get()->take(5);
        $lastProducts = Product::orderBy('created_at', 'desc')

            ->get()->take(5);
        $lastCustomer = User::orderBy('created_at', 'desc')
            ->get()->take(5);
        $data = [
            'categories' => $categories,
            'customers' => $customers,
            'products' => $products,
            'orders' => $orders,
            'ads' => $ads,
            'lastProducts' => $lastProducts,
           'lastCustomer' => $lastCustomer,
            'lastOrders' => $lastOrders,
//            'moreOrderedProduct' => ProductResource::collection($this['moreOrderedProduct']),
//            'moreCommentedProduct' => ProductResource::collection($this['moreCommentedProduct']),

        ];
        return $this->apiResponse(new AdminIndexResource($data));
    }


}
