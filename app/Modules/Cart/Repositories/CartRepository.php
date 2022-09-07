<?php namespace App\Modules\Cart\Repositories;

use App\Modules\Cart\Entities\Cart;
use App\Modules\Cart\Entities\CartItem;
use App\Modules\Cart\Entities\CartProduct;
use App\Modules\Cart\ValidationRules\ProductLimitQuantity;
use App\Modules\Cart\ValidationRules\ProductTypeService;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Transformers\ProductResource;
use App\Modules\Subscription\Entities\NormalSubscription;
use App\Modules\Subscription\Entities\Subscription;
use App\Modules\Subscription\Entities\SubscriptionSize;
use App\Modules\Subscription\Transformers\SubscriptionResource;
use App\Scopes\NormalProductScope;
use App\Services\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class CartRepository
{
    public function getUserCart($user)
    {
        return $user->cart->with(["user.addresses.area.city.country",'user.addresses.area.city','items']);
    }

    // store  new  instances of model
    public function storeCart($data)
    {
        $cart = $this->createCart();
        //---------------if subscription ----------------------------------
        if (isset($data['subscription_id']) && isset($data['type'])) {

            $cart->items()->detach();
            $cart->update([
                "type" => $data['type'],
                "subscription_id" => $data['subscription_id'],
            ]);
            if (isset($data['subscription_items'])) {
                foreach ($data['subscription_items'] as $item) {
                    $cart->subscriptionItems()->create([
                        'item_id' => $item,
                    ]);
                }
            }
        }
        //---------------if products ----------------------------------
        if (isset($data['items'])) {
            $cart->update([
                "type" => "items",
                "subscription_id" => null
            ]);
            $cart->subscriptionItems()->delete();
            $products = $this->getItemsData($data['items']);
            $items = collect($data['items'])->mapWithKeys(function ($item) use ($products, $cart) {
                $product = $products->where('id', $item['product_id'])->first();
                $price = $product->discount_price ?? $product->price;
                if (isset($item['additional_products'])) {
                    $productAdditionalPrice = $this->getAdditionalPrice($item['additional_products']);
                    // store additions
                    foreach ($item['additional_products'] as $additionId) {
                        $cart->additions()->create([
                            'addition_id' => $additionId,
                            'product_id' => $item['product_id']
                        ]);
                    }
                }
                return [
                    $item['product_id'] => [
                        'quantity' => $item['quantity'],
                        'price' => (isset($item['additional_products'])) ? ($price * $item['quantity']) + $productAdditionalPrice : $price * $item['quantity'],
                    ],
                ];
            });
            $cart->items()->sync($items);
        }
        return $cart;
    }

    public function cookiesItems($requests, $currency)
    {
        $newProducts = [];
        if (isset($requests['items'])) {
            $items = $requests['items'];
            $products = $this->getItemsData($items);
            //        dd($products)
            foreach ($items as $item) {
                $product = $products->where('id', $item['product_id'])->first();
                $additional_products = isset($item['additional_products']) ? $this->getAdditionalPrice($item['additional_products']) : 0;
                $product_price_with_additions = $product->price + $additional_products;
                $product = collect($product);
                $product['quantity'] = $item['quantity'];
                $product['price'] = round($product_price_with_additions, 2);
                $newProducts[] = $product;
            }
            $newProducts[] = $product;

        } elseif (isset($requests['type']) && $requests['type'] == 'custom') {
            $newProducts[] = new SubscriptionResource(SubscriptionSize::find($requests['subscription_id']));
        } elseif (isset($requests['type']) && $requests['type'] == 'normal') {
            $newProducts[] = new SubscriptionResource(NormalSubscription::find($requests['subscription_id']));
        }
        return $newProducts;
    }

    public function getAdditionalPrice($additions)
    {
        $sum = 0;
        $products = Product::withoutGlobalScope(NormalProductScope::class)->whereIn('id', $additions)->get();
        foreach ($products as $product) {
            $sum += $product->price;
        }
        return $sum;
    }

    public function getItemsData($items)
    {
        $productsIds = array_column($items, 'product_id');
        $products = Product::withoutGlobalScope(NormalProductScope::class)->find($productsIds);
        return ProductResource::collection($products);
    }

    // remove record from the database
    public function delete($id)
    {
        $cart = Auth::user()->cart;
        $cart->items()->detach($id);
        return $cart;
    }

    public function createCart()
    {
        $cart= Cart::with(["user.addresses.area.city.country",'user.addresses.area.city','items'])->updateOrcreate([
            'store_id' => app('Illuminate\Http\Request')->header('store_id'),
            'user_id' => auth()->id(),
            'ordered_at' => null
        ]);
        return $cart;
    }


    public function validations($items)
    {
        $validator = new Validator();
        foreach ($items as $item) {
            $validator->addRules([
                new ProductTypeService($item),
            ]);
        }


        return $validator;
    }
}
