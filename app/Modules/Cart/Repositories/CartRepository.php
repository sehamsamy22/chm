<?php namespace App\Modules\Cart\Repositories;

use App\Modules\Cart\Entities\Cart;
use App\Modules\Cart\Entities\CartItem;
use App\Modules\Cart\Entities\CartProduct;
use App\Modules\Cart\ValidationRules\ProductLimitQuantity;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Transformers\ProductResource;
use App\Scopes\NormalProductScope;
use App\Services\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class CartRepository
{
    public function getUserCart($user)
    {
        return $user->cart;
    }

    // store  new  instances of model
    public function storeCart($data)
    {
//        dd($data);
        $cart = $this->createCart();
        $products = $this->getItemsData($data['items']);
        $items = collect($data['items'])->mapWithKeys(function ($item) use ($products) {
            $product = $products->where('id', $item['product_id'])->first();
            $price = $product->discount_price ?? $product->price;
            return [
                $item['product_id'] => [
                    'quantity' => $item['quantity'],
                    'price' => $price * $item['quantity']
                ]
            ];
        });
//        dd($cart->items()->get(),$items);
        $cart->items()->sync($items);
        return $cart;
    }

    public function cookiesItems($items,$currency)
    {
        $products = $this->getItemsData($items);
        $newProducts = [];
        foreach ($items as $item) {
            $product = $products->where('id', $item['product_id'])->first();
//            dd($product['price'],exchangeRate($product['price'],$currency),round(exchangeRate($product['price'],$currency), 2));
            $product = collect($product);
            $product['quantity'] = $item['quantity'];
//            $product['price'] = $item['price'];
            $newProducts[] = $product;
        }
        return $newProducts;
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
        return Cart::updateOrcreate([
            'store_id' => app('Illuminate\Http\Request')->header('store_id'),
            'user_id' => auth()->id(),
            'ordered_at' => null
        ]);
    }

    public function validations($items)
    {
        $validator = new Validator();
        foreach ($items as $item) {
            $validator->addRules([
                new ProductLimitQuantity($item),
            ]);
        }


        return $validator;
    }
}
