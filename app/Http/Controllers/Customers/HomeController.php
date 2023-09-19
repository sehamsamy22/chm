<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerOpinionResource;
use App\Http\Resources\IndexResource;
use App\Models\CustomerOpinion;
use App\Modules\Ad\Entities\Ad;
use App\Modules\Ad\Transformers\AdResource;
use App\Modules\Address\Entities\Country;
use App\Modules\Blog\Entities\Blog;
use App\Modules\Category\Entities\Category;
use App\Modules\Page\Entities\Page;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Transformers\ProductCollection;
use App\Modules\Product\Transformers\ProductResource;
use App\Modules\Setting\Entities\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class HomeController extends Controller
{
    public function index()

    {
      dd(app('Illuminate\Http\Request')->header('store_id'));
        $appSettings = Setting::whereIn('name', ['appName', 'appDesc', 'copyright'])->pluck('value', 'name')->toArray();
        $socialSettings = Setting::where('module', 'social')->get();
        $footerLinks= Setting::whereIn('name', ['footerLinks'])->first();

        $countries = Country::select(['id', 'name', 'flag'])->with('stores')->get();
        $ads = Ad::all();
        $moreOrderedProduct = Product::select(['id', 'name', 'price', 'image'])->withCount('orders')->orderBy('orders_count', 'DESC')->get()->take(8);
        $moreCommentedProduct = Product::select(['id', 'name', 'price', 'image'])->withCount('comments')->orderBy('comments_count', 'DESC')->get()->take(8);
        $categories = Category::whereNull('parent_id')->with('subcategories','options','products')->get()->take(5);
        $blogs = Blog::get()->take(4);
        $data = [
            'app_settings' => $appSettings,
            'social_settings' => $socialSettings,
            'ads' => $ads,
            'moreOrderedProduct' => ProductCollection::collection($moreOrderedProduct),
            'moreCommentedProduct' => ProductCollection::collection($moreCommentedProduct),
            'categories' => $categories,
            'countries' => $countries,
            'blogs' => $blogs,
            'footerLinks'=>json_decode($footerLinks->value) 
        ];
        return $this->apiResponse(new IndexResource($data));
    }

    public function show($id)
    {
        $page = Page::find($id);
        return $this->apiResponse(new PageInfoResource($page));
    }

    public function moreOrderedProduct()
    {

        $moreOrderedProduct = Product::with('orders')->get()->sortByDesc(function ($product) {
            return $product->orders->count();
        })->take(20);
        return $this->apiResponse(ProductResource::collection($moreOrderedProduct));
    }

    public function test()

    {   $urlParamsAsync = [
        "source_amount:1",
        "source_currency:/m/02d1cm",
        "target_currency:/m/04phzg",
        "lang:ar",
        "country:sa",
        "disclaimer_url:https://www.google.com/intl/ar/googlefinance/disclaimer/",
        "period:1M",
        "_fmt:pc"
    ];
        $urlParamsAsync = implode(',', $urlParamsAsync);
        $data = Http::get("https://www.google.com/async/currency_v2_update?async={$urlParamsAsync}");
        dd(Str::between($data->body(), 'data-exchange-rate="', '"><div class="vk_sh'));
    }
    public function opinions()
    {
        $customerOpinions = CustomerOpinion::all();
        return $this->apiResponse(CustomerOpinionResource::collection($customerOpinions));
    }

}
