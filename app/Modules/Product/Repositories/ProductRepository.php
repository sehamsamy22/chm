<?php namespace App\Modules\Product\Repositories;

use App\Modules\Product\Entities\Product;
use App\Scopes\NormalProductScope;
use Illuminate\Support\Facades\Auth;

class ProductRepository
{
    // Get all instances of model
    public function all()
    {
        return Product::active()->orderBy('id', 'DESC')->get();
    }

    public function subscription()
    {
        return Product::where('type', 'subscription')->orderBy('id', 'DESC')->get();
    }

    // store  new  instances of model
    public function storeProduct($data)
    {
        $data['store_id'] = app('Illuminate\Http\Request')->header('store_id');
        $data['creator_id'] = Auth::user()->id;
        $product = Product::create($data);
        if (isset($data['tags'])) {
            foreach ($data['tags'] as $tag) {
                $product->tags()->create(['tag' => $tag]);
            }
        }
        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                $product->images()->create(['image' => $image]);
            }
        }
        if ($product->is_package == 1) {
            foreach ($data['package_categories'] as $categoryId) {
                $product->packageCategories()->create(['category_id' => $categoryId]);
            }
        }
        if ($product->bundle == 1) {
            foreach ($data['bundle_products'] as $productId) {
                $product->bundles()->create(['product_id' => $productId]);
            }
        } elseif (isset($data['options_values'])) {
            $product->options()->sync($data['options_values']);
        }
        return $product;
    }

    // update  new  instances of model
    public function update($data, $id)
    {
        $data['store_id'] = app('Illuminate\Http\Request')->header('store_id');
        $product = Product::withoutGlobalScope(NormalProductScope::class)->where('id', $id)->first();
        $product->update($data);
        $product->tags()->delete();
        $product->images()->delete();
        $product->packageCategories()->delete();
        foreach ($data['tags'] as $tag) {
            $product->tags()->create(['tag' => $tag]);
        }
        foreach ($data['images'] as $image) {
            $product->images()->create(['image' => $image]);
        }
        if ($product->is_package == 1) {
            foreach ($data['package_categories'] as $categoryId) {
                $product->packageCategories()->create(['category_id' => $categoryId]);
            }
        }
        if ($product->bundle == 1) {
            $product->bundles()->delete();
            foreach ($data['bundle_products'] as $productId) {
                $product->bundles()->create(['product_id' => $productId]);
            }
        } elseif (isset($data['options_values'])) {
            $product->options()->sync($data['options_values']);
        }
        return $product;
    }

    public function getComments($id)
    {
        $product = Product::findOrFail($id);
        return $product->comments()->get();
    }

    public function getRates($id)
    {
        $product = Product::findOrFail($id);
        return $product->rates()->get();
    }

    public function getWishes($id)
    {
        $product = Product::findOrFail($id);
        return $product->wishes()->get();
    }

    public function getProductOptions($products, $options)
    {
        $productsArray = [];
        return $productsArray;
        //        $uniqueOptions = $productOptions->unique('id')->map(function ($option) {
//            return [
//                'id' => $option->id,
//                'name' => $option->name,
//                'input_type' => $option->input_type,
//            ];
//        });
//        $productOptionsValues = $productOptions->pluck('pivot');
//        $options = [];
//        foreach ($uniqueOptions as $option) {
//            foreach ($productOptionsValues as $pivotValue) {
//                if ($pivotValue->option_id != $option['id']) continue;
//                if (empty($option['values'])) $option['values'] = [];
//                if (!in_array($pivotValue->value, $option['values'])) {
//                    $option['values'][] = $pivotValue->value;
//                }
//            }
//            $options[] = $option;
//        }
//        return $options;

    }

    public function filterProducts($params): object
    {
        // QFETHING  PRODUCTS
        $products = Product::with(['options', 'category.options', 'lists', 'images', 'comments', 'rates', 'brand'])
            ->withoutGlobalScope(NormalProductScope::class)->where('type', '!=', 'package_addition')->orderBy('id', 'DESC');
        // APPLY  REQUESTS  FILTER
        //SEARCH  WORD
        if (isset($params['search_word'])) {
            $products->where(function ($q) use ($params) {
                $q->where('name', 'like', "%{$params['search_word']}%");
                $q->orWhere('description', 'like', "%{$params['search_word']}%");
                $q->orWhere('SKU', 'like', "%{$params['search_word']}%");
            });
        }
//        CATEGORY  FILTER
        if (isset($params['main_category']) && empty($params['sub_categories'])) {
            $mainCategory = $params['main_category'];
            $products->whereHas('category', function ($q) use ($mainCategory) {
                $q->where('parent_id', $mainCategory);
            });
        }
   
        if (!empty($params['sub_categories'])) {
            $products->whereIn('category_id', $params['sub_categories']);
        }
        // BRAND  FILTER
        if (!empty($params['brand'])) {
            $products->where('brand_id', $params['brand']);
        }
        // PRODUCT TYPE  FILTER
        if (!empty($params['type'])) {
            $products->where('type', $params['type']);
        }
        // PRICE  RANGE  FILTER
        if (isset($params['min_price']) & isset($params['max_price'])) {
            $min_price = !isset($params['min_price']) ? 0 : $params['min_price'];
            $max_price = !isset($params['max_price']) ? 0 : $params['max_price'];
            $products->whereBetween('price', [$min_price, $max_price]);
        }
        if (isset($params['min_price']) & !isset($params['max_price'])) {
            $min_price = !isset($params['min_price']) ? 0 : $params['min_price'];
            $products->where('price', '= >', $min_price);
        }
   if (isset($params['package_categories'])&& $params['package_categories']== true) {
           $products->has('packageCategories');
  
        }
//       if (!$pagination) return $products->get();
//      if (!isset($params['main_category'])) return $products->get();
//         OPTIONS  FILTER
        if (empty($products)) return $products->get();
        if (!isset($params['options'])) return $products->get();
        $products = $products->get();

        foreach ($products as $key => $product) {
            $productValues = $product->values->pluck('id')->toArray();
            foreach ($params['options'] as $option) {
                foreach ($option['values'] as $value) {
                    if (!in_array($value, $productValues)) {
                        unset($products[$key]);
                    }
                }
            }
        }
        return $products;
    }

    public function getProductsHasAnyTypeOfDiscounts()
    {
        $products = Product::whereNotNull('discount_price')->orWhereHas('lists', function ($list) {
            $list->whereHas('promotions');
        })->get();
        return $products;
    }
}
