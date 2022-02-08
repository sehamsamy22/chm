<?php namespace App\Modules\Order\Repositories;

use App\Modules\Address\Entities\Address;
use App\Modules\Cart\Entities\Cart;
use App\Modules\Cart\Repositories\CartRepository;
use App\Modules\Coupon\Entities\Coupon;
use App\Modules\Coupon\Repositories\CouponRepository;
use App\Modules\Order\Entities\Order;
use App\Modules\Order\Entities\OrderInvoiceLog;
use App\Modules\Order\ValidationRules\CartExists;
use App\Modules\Order\ValidationRules\ProductStocks;
use App\Modules\Order\ValidationRules\serviceOrder;
use App\Modules\Payment\Entities\PaymentMethod;
use App\Modules\Payment\Entities\ShippingMethod;
use App\Services\Validation\Validator;

class OrderRepository
{
    // model property on class instances
    protected $order;
    private $couponRepository;
    private $cartRepository;
    private $orderInvoiceFees;

    // Constructor to bind model to repo
    public function __construct(Order $order, CouponRepository $couponRepository, CartRepository $cartRepository)
    {
        $this->Order = $order;
        $this->couponRepository = $couponRepository;
        $this->cartRepository = $cartRepository;
        $this->orderInvoiceFees = [
            'baseCost' => ['type' => 'add', 'cost' => 0],
            'couponDiscount' => ['type' => 'sub', 'cost' => 0],
            'shippingCost' => ['type' => 'add', 'cost' => 0]
        ];
    }

    // Get all instances of model
    public function items($id)
    {
        return Cart::find($id);
    }

    /**
     * @param $coupon
     * @return mixed
     */
    public function calculateCustomizationsDiscount($coupon, $user)
    {
        // check coupon customization and get discounted products ids
        $couponProducts = $this->couponRepository->checkCustomizationsCoupon($user, $coupon);
        if (empty($couponProducts)) return 0;
        // calculate coupon discount
        return $this->calculateCouponDiscount($coupon, $user, $couponProducts);
    }

    public function calculateCouponDiscount($coupon, $user, $couponProducts = [])
    {
        if (!$user->cart) return false;
        $items = $user->cart->items->whereNull('discount_price');
        $discount = 0;
        // TODO enhance next lines
        if (!$couponProducts) return $discount;
        if ($coupon->type == 'percent') {
            foreach ($items as $product) {
                if (in_array($product->id, $couponProducts)) {
                    $amount = ($coupon->amount) * $product->price / 100;
                    $discount += $amount * $product->pivot->quantity;

                }
            }

        } else {
            $discount = $coupon->amount;
        }
        return $discount;
    }

    public function validations($request, $user)
    {
        $validator = new Validator();
        $validator->addRules([
            new CartExists($user),
            new ProductStocks($user->cart),
            new serviceOrder($request,$user->cart)

        ]);
        return $validator;
    }

    public function calculateOrderFullTotal($data, $user)
    {
        $this->orderInvoiceFees['baseCost']['cost'] = $data['total'] = $this->calculateOrderProductsTotal($user);
        if (isset($data['coupon_discount'])) {
            $data['total'] -= $this->orderInvoiceFees['couponDiscount']['cost'] = $data['coupon_discount'];
        }
        // $data['total'] += $this->orderInvoiceFees['shippingCost']['cost'] = $this->getShippingPrice($data['address_id']);
        return $data['total'];
    }

    public function calculateOrderProductsTotal($user)
    {
        $items = $this->getCartItems($user,false);
        return $items->reduce(function ($carry, $item) {
            $price = $item->discount_price ?? $item->price;
            return $carry + ($price * $item->pivot->quantity);
        });
    }

    public function getCartItems($user, $forOrder = true)
    {
        $cart = $this->cartRepository->getUserCart($user);
        if (!$forOrder) return $cart->items;
        return $cart->items->map(function ($item) {
            return [
                'product_id' => $item->id,
                'quantity' => $item->pivot->quantity,
                'price' => $item->pivot->price
            ];
        })->toArray();
    }

    public function getShippingPrice($id)
    {
        $address = Address::find($id);
        if ($address->area->shipping_price) return $address->area->shipping_price;
        if ($address->area->city->shipping_price) return $address->area->city->shipping_price;
        return getsetting('generalShippingPrice');
    }

    public function createOrder($data, $user, $status = null,$transaction_id = null)
    {
       // dd($data, $user, $status = null,$transaction_id);
        // TODO separate next line to calculateOrderTotal()
        // TODO area for creation online payment methods
        $data['unique_id'] = Order::getRandomUniqueIdForModel();
        $data['method_id'] = $data['payment_method_id'];
        $data['transaction_id'] = $transaction_id;
        $data['amount'] = $this->calculateOrderProductsTotal($user);
        $order = $user->orders()->create($data);
        $cartItems = $this->getCartItems($user);
        $order->products()->attach($cartItems);
        $this->changeOrderStatus($order, $status ?? Order::PENDING);
        $this->updateProductStock($order);
        $this->createOrderInvoice($order, $data['total']);
        if (isset($data['prom_code'])) {
            $coupon = Coupon::where('prom_code', $data['prom_code'])->first();
            $order->coupon()->create(['coupon_id' => $coupon->id]);
            $coupon->increment('used_count');
        }
        $user->cart->ordered_at = now();
        $user->cart->order_id = $order->id;
        $user->cart->save();
        return $order;
    }

    public function changeOrderStatus($order, $status)
    {
        if ($status != Order::PENDING) $order->update(['status' => $status]);
        $order->history()->create(['status' => $status]);
        // TODO check if order status is cancelled and payment method was online
        if ($status != Order::CANCELLED || $status != Order::RETURNED) return false;
        $paymentMethod = PaymentMethod::find($order->method_id);
        if (!$paymentMethod->is_online == 0) return false;
        // TODO update wallet amount and create wallet log
        $wallet = $order->user->wallet;
        $order->user->wallet()->update(['amount' => $wallet->amount + $order->total]);
        $wallet->logs()->create(['order_id' => $order->id, 'amount' => $order->total, 'status' => 1]);
    }

    public function updateProductStock($order)
    {
        foreach ($order->products as $product) {
            $stock = null;
            switch ($order->status) {
                case Order::PENDING :
                    $stock = $product->stock - $product->pivot->quantity;
                    break;
                case Order::CANCELLED :
                    $stock = $product->stock + $product->pivot->quantity;
                    break;
            }
            if ($stock) $product->update(['stock' => $stock]);
        }
    }

    // remove record from the database

    public function createOrderInvoice($order, $total)
    {
        $invoice = $order->invoice()->create(['total' => $total, 'coupon_id' => optional($order->coupon)->coupon_id]);
        $fees = [];
        foreach ($this->orderInvoiceFees as $key => $fee) {
            $fees[] = [
                'invoice_id' => $invoice->id,
                'fees_name' => $key,
                'fees_type' => $fee['type'],
                'cost' => $fee['cost']
            ];
        }
        OrderInvoiceLog::insert($fees);
    }

    // TODO enhance this method

    public function delete($id)
    {
        return $this->Order->destroy($id);
    }

    // Get all instances of model

    public function all()
    {
        return $this->Order->all();
    }

    public function filterOrders($params): object
    {
        $orders = Order::query();
        if (isset($params['unique_id'])) {
            $orders->where(function ($q) use ($params) {
                $q->where('unique_id', $params['unique_id']);
            });
        }
        if (isset($params['status'])) {
            $orders->where(function ($q) use ($params) {
                $q->where('status', $params['status']);
            });
        }
        if (isset($params['city']) && empty($params['area'])) {
            $city = $params['city'];
            $orders->whereHas('address', function ($q) use ($city) {
                $q->whereHas('area', function ($q) use ($city) {
                    $q->where('city_id', $city);
                });
            });
        }
        if (isset($params['area'])) {
            $area = $params['area'];
            $orders->whereHas('address', function ($q) use ($area) {
                $q->whereHas('area', function ($q) use ($area) {
                    $q->where('area_id', $area);
                });
            });
        }
        if (isset($params['form']) && isset($params['to'])) {
            $orders->whereBetween('created_at', [$params['form'], $params['to']]);
        }
        if (isset($params['form']) && empty($params['to'])) {
            $orders->where('created_at', '>=', [$params['form']]);
        }
        if (isset($params['to']) && empty($params['form'])) {
            $orders->where('created_at', '<=', [$params['to']]);
        }
        $pagination = $params['page'] ?? null;
        if (!$pagination) return $orders->get();
        return $orders->paginate($params['page_limit'] ?? 12);
    }


}
