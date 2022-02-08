<?php namespace App\Modules\Coupon\Repositories;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Category\Entities\Category;
use App\Modules\Coupon\Entities\Coupon;
use App\Modules\Coupon\Notifications\UsersCoupon;
use App\Modules\Coupon\ValidationRules\CouponCategoryValid;
use App\Modules\Coupon\ValidationRules\CouponExists;
use App\Modules\Coupon\ValidationRules\CouponExpireValid;
use App\Modules\Coupon\ValidationRules\CouponLimitUsageValid;
use App\Modules\Coupon\ValidationRules\CouponProductValid;
use App\Modules\Coupon\ValidationRules\CouponUserExists;
use App\Modules\Coupon\ValidationRules\CouponUserValid;
use App\Modules\Coupon\ValidationRules\ProductLimitQuantity;
use App\Modules\Product\Entities\Product;
use App\Services\Validation\Validator;
use Illuminate\Support\Facades\Notification;


class CouponRepository
{
    // Get all instances of model
    public function all()
    {
        return Coupon::active()->get();
    }


    // store  new  instances of model
    public function storeCoupon($data)
    {
        $coupon = Coupon::create($data);
        if (isset($data['coupon_customization_type'])) {
            switch ($data['coupon_customization_type']) {
                case 'products' :
                    $couponCustomizationType = Product::class;
                    break;
                case 'categories' :
                    $couponCustomizationType = Category::class;
                    break;
                case 'users' :
                    $couponCustomizationType = User::class;
                    break;
            }
            $this->createCouponCustomization($coupon, $couponCustomizationType, $data['coupon_customization_ids']);
        }
        return $coupon;
    }


    // update  new  instances of model
    public function update($data, $id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update($data);
        if ($data['coupon_customization_type']) {
            $coupon->options()->delete();
            switch ($data['coupon_customization_type']) {
                case 'products' :
                    $couponCustomizationType = Product::class;
                    break;
                case 'categories' :
                    $couponCustomizationType = Category::class;
                    break;
                case 'users' :
                    $couponCustomizationType = User::class;
                    break;
            }

            $this->createCouponCustomization($coupon, $couponCustomizationType, $data['coupon_customization_ids']);
        }
        return $coupon;
    }

    public function createCouponCustomization($coupon, $modelName, $ids)
    {

        foreach ($ids as $id) {
            $coupon->customizations()->create([
                'model_id' => $id,
                'model_type' => $modelName
            ]);
            if ($modelName == User::class) {
                foreach ($coupon->customizations as $customization) {
                    Notification::send($customization->model, new UsersCoupon($coupon));
                }
            }
        }

    }

    // remove record from the database
    public function delete($id)
    {
        return Coupon::destroy($id);
    }

    public static function validations($coupon, $user)
    {

        $validator = new Validator();
        $validator->addRules([
            new CouponExists($coupon, $user),
            new CouponUserExists($coupon, $user),
            new CouponExpireValid($coupon, $user),
            new CouponLimitUsageValid($coupon, $user),
            new CouponUserValid($coupon, $user),
            new CouponCategoryValid($coupon, $user),
            new CouponProductValid($coupon, $user),
        ]);
        return $validator;
    }

    public function checkCustomizationsCoupon($user, $coupon)
    {
        if (!$user->cart) return Controller::errorResponse("cart is empty", [], 422);
        $items = $user->cart->items->whereNull('discount_price');
        $userCartProducts = $items->pluck('id')->toArray();
        //check  if coupon have any customizations.
        $customizations = $coupon->customizations;
        if (!count($customizations)) return $userCartProducts;
        //check if isset users in this coupon and auth user in their.
        $couponUsers = $customizations->where('model_type', User::class)->pluck('model_id')->toArray();
        if (count($couponUsers) && !in_array($user->id, $couponUsers)) return [];
        // check if coupon has customizations products or categories
        $couponCategories = $customizations->where('model_type', Category::class)->pluck('model_id')->toArray();
        $couponProducts = $customizations->where('model_type', Product::class)->pluck('model_id')->toArray();
        if (empty($couponCategories) && empty($couponProducts)) return $userCartProducts;
        //check if coupon match products or categories of user cart.
        $userCartCategories = $items->pluck('category_id')->toArray();
        $matchedProducts = array_intersect($couponProducts, $userCartProducts);
        $matchedCategories = array_intersect($couponCategories, $userCartCategories);
        if (!count($matchedProducts) && !count($matchedCategories)) return [];
        $cartCategoryProducts = $items->whereIn('category_id', $matchedCategories)->pluck('id')->toArray();
        if (count($matchedProducts) || count($matchedCategories)) return array_unique(array_merge($matchedProducts, $cartCategoryProducts));
    }




}
