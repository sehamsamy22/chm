<?php

namespace App\Modules\Product\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Category\Entities\Category;
use App\Modules\Category\Transformers\CategoryDetailResource;
use App\Modules\Product\Entities\Brand;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Product\Transformers\BrandResource;
use App\Modules\Product\Transformers\ProductResource;
use App\Modules\Product\Transformers\RateResource;
use App\Scopes\NormalProductScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function filtration(Request $request)
    {
        $request->validate([
            "sub_categories" => "array",
            "main_category" => "sometimes|exists:categories,id",
            "list_id" => "sometimes|exists:lists,id",
            "type"=>"sometimes|in:normal,subscription,service,additions",
        ]);
        $products = $this->productRepository->filterProducts($request->all());
        $total = $products->count();
        $category = isset($request->main_category) ? Category::find($request->main_category) : null;
        return $this->apiResponse([
            'category' => ($category) ? new CategoryDetailResource($category) : null,
            'products' => ProductResource::collection($products->paginate($request['page_limit'] ?? 12)),
            'total' => $total,
            'last_pages' => isset($request['page_limit']) ? ceil((!empty($products) ? $total : 0) / $request['page_limit']) : 0
        ]);
    }

    public function show($id)
    {

        $product = Product::withoutGlobalScope(NormalProductScope::class)->findOrFail($id);

        return $this->apiResponse(new ProductResource($product));
    }

    public function store_comment(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'comment' => 'required|string|max:255',
        ]);
        $product = Product::find($validated['product_id']);
        $user = User::find($validated['user_id']);
        $product->comments()->attach($user, [
            'comment' => $validated['comment'],
        ]);
        return $this->apiResponse(new RateResource($product->comments));
    }

    public function storeRate(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rate_avg' => 'required',
            'product_negatives' => 'nullable|string|max:255',
            'product_positives' => 'nullable|string|max:255',

        ]);
        $product = Product::findOrFail($validated['product_id']);
        $rate = $product->rates()->attach(Auth::user(), [
            'rate_avg' => $validated['rate_avg'],
            'product_negatives' => $validated['product_negatives'],
            'product_positives' => $validated['product_positives'],
        ]);
//        dd($product->rates->id);
        return $this->apiResponse(new RateResource($product->rates->last()));

    }

    public function storeWish(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);
        Auth::user()->wishes()->toggle([$validated['product_id'] => ['type' => 'wish']]);
        return $this->apiResponse(['message' => "Your Wishes list is updated"]);

    }

    public function storeCompare(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $user = Auth::user();
        $userCompares = $user->wishes()->wherePivot('type', 'compare')->pluck('product_id')->toArray();
        if (count($userCompares) >= 3)
            return $this->apiResponse(['message' => "you  have  Already 3 products"]);
        if (in_array($data['product_id'], $userCompares))
            return $this->apiResponse(['message' => "you  have  Already this product before"]);
        $user->compares()->toggle([$data['product_id'] => ['type' => 'compare']]);
        return $this->apiResponse(['message' => "Your compare list is updated"]);
    }

    public function getWishList($type = 'wish')
    {
        $user = Auth::user();
        $userWishes = $type == 'wish' ? $user->wishes : $user->compares;
        return $this->apiResponse(ProductResource::collection($userWishes));
    }

    public function productsHasPromotions()
    {
        $productPromotions = $this->productRepository->getProductsHasAnyTypeOfDiscounts();
        return $this->apiResponse(ProductResource::collection($productPromotions));
    }

    public function subscriptions()
    {
        $products = Product::withoutGlobalScope(NormalProductScope::class)->where('type', 'subscription')->get();
        return $this->apiResponse(ProductResource::collection($products));
    }
    public function services()
    {
        $services = Product::withoutGlobalScope(NormalProductScope::class)->where('type', 'service')->get();
        return $this->apiResponse(ProductResource::collection($services));
    }
    public function brands()
    {
        $brands = Brand::all();
        return $this->apiResponse(BrandResource::collection($brands));
    }

    public function removeFromCompareList(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);
        Auth::user()->compares()->detach([$validated['product_id'] ]);
        return $this->apiResponse(['message' => "product removed successfully"]);

    }
}
