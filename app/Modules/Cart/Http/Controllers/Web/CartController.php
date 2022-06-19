<?php
namespace App\Modules\Cart\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Modules\Cart\Http\Requests\CartRequest;
use App\Modules\Cart\Repositories\CartRepository;
use App\Modules\Cart\Transformers\CartResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $CartRepository;

    public function __construct(CartRepository $CartRepository)
    {
        $this->CartRepository = $CartRepository;
    }

    public function items()
    {
        $user = Auth::user();
        $cart = $this->CartRepository->getUserCart($user);
        if (!$cart) return $this->errorResponse('Your cart is empty');
        return $this->apiResponse(new CartResource($cart));
    }

    public function getCookiesItems(CartRequest $request)
    {
        $currency = $request->header('currency');
        $requestItems=$request->items;
        $items = $this->CartRepository->cookiesItems($requestItems,$currency);
        return $this->apiResponse($items);
    }

    public function store(CartRequest $request)
    {
        $requests = $request->validated();
//        $validations = $this->CartRepository->validations($request['items'])->validate()->first();
//        if ($validations) return $this->errorResponse($validations->getMessage(), [], 422);
        $cart = $this->CartRepository->storeCart($requests);
        return $this->apiResponse(new CartResource($cart));
    }

    public function deleteItem($id)
    {
        $cart = $this->CartRepository->delete($id);
        return $this->apiResponse(new CartResource($cart));
    }

    public function deleteCart()
    {
        Auth::user()->cart()->delete();
        return $this->apiResponse(['message' => "Cart deleted successfully"]);
    }


}
