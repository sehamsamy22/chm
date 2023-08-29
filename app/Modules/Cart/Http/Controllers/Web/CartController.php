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
        $requests=$request->all();
        $items = $this->CartRepository->cookiesItems($requests,$currency);
        return $this->apiResponse($items);
    }

    public function store(CartRequest $request)
    {
        $requests = $request->validated();
        if (isset($request['items'])) {
            $validations = $this->CartRepository->validations($request['items'])->validate()->first();
            if ($validations) return $this->errorResponse($validations->getMessage(), [], 422);
        }
        $cart = $this->CartRepository->storeCart($requests);
        // dd($cart);
        return $this->apiResponse(new CartResource($cart));
    }

    public function deleteItem($id)
    {
        $cart =Auth::user()->cart;
        if ($cart->type=='items'){
            $newCart = $this->CartRepository->delete($id);
        }  else{
            $cart->update([
                'subscription_id'=>null
            ]);
            $newCart =$cart;
        }
        return $this->apiResponse(new CartResource($newCart));
    }

    public function deleteCart()
    {
        Auth::user()->cart->delete();
        return $this->apiResponse(['message' => "Cart deleted successfully"]);
    }


}
