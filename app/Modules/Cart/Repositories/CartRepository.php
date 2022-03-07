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
        $cart = $this->createCart();
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
        return $cart;
    }

    public function cookiesItems($items, $currency)
    {
        $products = $this->getItemsData($items);
//        dd($products)
        $newProducts = [];
        foreach ($items as $item) {
//            dd($items);
            $product = $products->where('id', $item['product_id'])->first();
//            dd($product['price'],exchangeRate($product['price'],$currency),round(exchangeRate($product['price'],$currency), 2));
            $product_price_with_additions =$product->price + $this->getAdditionalPrice($item['additional_products']);
            $product = collect($product);
            $product['quantity'] = $item['quantity'];
            $product['price'] =round($product_price_with_additions,2);
            $newProducts[] = $product;
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
