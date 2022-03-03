<?php

namespace App\Modules\Product\Http\Controllers\Admin;


use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Modules\Category\Entities\Category;
use App\Modules\Coupon\ValidationRules\ValidationError;
use App\Modules\Product\Entities\Product;
use App\Modules\Product\Http\Requests\ProductRequest;
use App\Modules\Product\Repositories\ProductRepository;
use App\Modules\Product\Transformers\CommentResource;
use App\Modules\Product\Transformers\ProductResource;
use App\Modules\Product\Transformers\RateResource;
use App\Scopes\NormalProductScope;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $ProductRepository;

    public function __construct(ProductRepository $ProductRepository)
    {
        $this->ProductRepository = $ProductRepository;
//        $this->middleware('permission:product-list', ['only' => ['index', 'show']]);
//        $this->middleware('permission:product-create', ['only' => ['create', 'store']]);
//        $this->middleware('permission:product-edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:product-delete', ['only' => ['destroy']]);
//        $this->middleware('permission:product-export-excel', ['only' => ['exportExcel']]);
//        $this->middleware('permission:product-import-excel', ['only' => ['importExcel']]);
    }

    public function index(Request $request)
    {
        $products = Product::withoutGlobalScope(NormalProductScope::class)->whereIn('type',['normal','package_addition'])->get();
        $total = $products->count();
        return $this->apiResponse(['products'=>ProductResource::collection($products->paginate($request['pageLimit'])),
            'total' => $total,
            'page'=>$request['page'],
            'pageLimit'=>$request['pageLimit'],
            'lastPages' => isset($request['pageLimit']) ? ceil((!empty($products) ? $total : 0) / $request['pageLimit']) : 0]);
    }

    /**
     * @param ProductRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $category = Category::find($request->category_id);
        if ($category->parent_id == null) {
            throw ValidationException::withMessages(['main_category' => "use sub category instead of main category"]);
        }
        if ($category->have_additions == 1 & $request->type !='additions') {
            throw ValidationException::withMessages(['type' => "Product type must be additions"]);
        }
        $product = $this->ProductRepository->storeProduct($request->validated());
        return $this->apiResponse(new ProductResource($product));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::withoutGlobalScope(NormalProductScope::class)->find($id);
        return $this->apiResponse(new ProductResource($product));
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
    public function update(ProductRequest $request, $id)
    {
        $product = $this->ProductRepository->update($request->all(), $id);
        return $this->apiResponse(new ProductResource($product));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $product = Product::withoutGlobalScope(NormalProductScope::class)->where('id', $id)->first();
        $product->delete();
        return $this->apiResponse(ProductResource::collection(Product::all()));
    }

    public function getComments($id)
    {
        $comments = $this->ProductRepository->getComments($id);
        return $this->apiResponse(CommentResource::collection($comments));
    }

    public function getRates($id)
    {
        $rates = $this->ProductRepository->getRates($id);
        return $this->apiResponse(RateResource::collection($rates));
    }

    public function exportExcel()
    {
        $date = date('Y-m-d-H-i-s');
        Excel::store(new ProductsExport(), "excels/products/products-{$date}.xlsx", 'public');
        return $this->apiResponse(['file_url' => asset("storage/excels/products/products-{$date}.xlsx")]);
    }

    public function importExcel(Request $request)
    {
        Excel::import(new ProductsImport(), request()->file('file'));
        return $this->apiResponse(['massage' => "import successfully"]);

    }

    public function subscriptions(Request $request)
    {
        $subscriptions = Product::withoutGlobalScope(NormalProductScope::class)->where('type', 'subscription')->get();
        $total = $subscriptions->count();
        return $this->apiResponse([
            "subscriptions" => ProductResource::collection($subscriptions->paginate($request['pageLimit'])),
            'total' => $total,
            'page'=>$request['page'],
            'pageLimit'=>$request['pageLimit'],
            'lastPages' => isset($request['pageLimit']) ? ceil((!empty($subscriptions) ? $total : 0) / $request['pageLimit']) : 0]);


    }

        public function services(Request $request)
    {
        $services = Product::withoutGlobalScope(NormalProductScope::class)->where('type', 'service')->paginate($request['pageLimit']);
        $total = $services->count();
        return $this->apiResponse([
            "services" => ProductResource::collection($services),
            'total' => $total,
            'page'=>$request['page'],
            'pageLimit'=>$request['pageLimit'],
            'lastPages' => isset($request['pageLimit']) ? ceil((!empty($services) ? $total : 0) / $request['pageLimit']) : 0]);
    }

    public function additions(Request $request)
    {
        $additions = Product::withoutGlobalScope(NormalProductScope::class)->where('type', 'additions')->paginate($request['pageLimit']);
        $total = $additions->count();
        return $this->apiResponse([
            "additions" => ProductResource::collection($additions),
            'total' => $total,
            'page'=>$request['page'],
            'pageLimit'=>$request['pageLimit'],
            'lastPages' => isset($request['pageLimit']) ? ceil((!empty($additions) ? $total : 0) / $request['pageLimit']) : 0]);
    }
}
