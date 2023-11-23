<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\DogItem;
use App\Models\DogProductItem;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function checkCart()
    {
        $cartcheck = Cart::where('user_id', auth()->id())->first();
        if (!$cartcheck) {
            $cart = new Cart();
            $cart->user_id = auth()->id();
            $cart->total_price = 0;
            $cart->total_amount = 0;
            $cart->save();
            return $cart;
        }
        return $cartcheck;
    }

    public function getCarts()
    {
        $user = User::with('carts')->find(auth()->id());

        // Kiểm tra xem người dùng có tồn tại không
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }
        // Lấy danh sách giỏ hàng của người dùng
        if ($user->role == 'user') {
            return ApiResponse::ok($user->carts->load('cartDetails'));
        } else if ($user->role == 'admin') {
            return ApiResponse::ok(Cart::with('cartDetails')->get());
        }
    }

    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'dog_item_id' => 'nullable|numeric',
                'dog_product_item_id' => 'nullable|numeric',
                'quantity' => 'nullable|numeric|min:1',
            ]);
            $cart = CartController::checkCart()->load('cartDetails');
            $dogid = request()->input('dog_item_id');
            if ($dogid) {
                $dog = DogItem::find($dogid);
                if (!$dog)
                    return ApiResponse::notfound('Không tìm thấy sản phẩm');
                if ($cart->cartDetails->where('dog_item_id', $dogid)->first())
                    return response()->json(['message' => 'Không còn đủ số lượng sản phẩm']);
                $cartdt = new CartDetail();
                $cartdt->cart_id = $cart->id;
                $cartdt->dog_item_id = $dogid;
                $cartdt->quantity = 1;
                $cartdt->price = $dog->Price;
                $cartdt->save();
                $cart->total_amount += 1;
                $cart->total_price += $dog->Price;
                $cart->save();
                return ApiResponse::ok('Sản phẩm đã được thêm vào giỏ hàng');
            }
            $itemid = request()->input('dog_product_item_id');
            if ($itemid) {
                $item = DogProductItem::find($itemid);
                if (!$item) return ApiResponse::notfound('Không tìm thấy sản phẩm');
                if ($item->Quantity < request()->input('quantity'))
                    return response()->json(['message' => 'Không còn đủ số lượng sản phẩm']);
                $checkcartdt = $cart->cartDetails->where('dog_product_item_id', $itemid)->first();
                if ($checkcartdt) {
                    $checkcartdt->quantity += $request->input('quantity');
                    if ($item->Quantity < $checkcartdt->quantity)
                        return response()->json(['message' => 'Không còn đủ số lượng sản phẩm']);
                    $checkcartdt->price = $checkcartdt->quantity * $item->Price;
                    $cart->total_amount += $request->input('quantity');
                    $cart->total_price += $item->Price * $request->input('quantity');
                    $cart->save();
                    $checkcartdt->save();
                    return ApiResponse::ok('Sản phẩm đã được thêm vào giỏ hàng');
                }
                $cartdt = new CartDetail();
                $cartdt->cart_id = $cart->id;
                $cartdt->dog_product_item_id = $itemid;
                $cartdt->quantity = request()->input('quantity');
                $cartdt->price = $item->Price * $cartdt->quantity;
                $cartdt->save();
                $cart->total_amount += $cartdt->quantity;
                $cart->total_price += $cartdt->price;
                $cart->save();
                return ApiResponse::ok('Sản phẩm đã được thêm vào giỏ hàng');
            }
        } catch (\Exception $e) {
            return ApiResponse::badrequest("Sản phẩm được thêm không thành công.");
        }
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'dog_product_item_id' => 'nullable|numeric',
            'quantity' => 'required|numeric|min:1'
        ]);
        $cart = CartController::checkCart()->load('cartDetails');
        $newquantity = $request->input('quantity');
        $itemid = $request->input('dog_product_item_id');
        $item = DogProductItem::find($itemid);
        $cartdt = $cart->cartDetails->where('dog_product_item_id', $itemid)->first();
        //Kiểm tra sản phẩm trong giỏ hàng
        if (!$cartdt)
            return ApiResponse::notfound('Sản phẩm không tồn tại trong giỏ hàng');
        //Kiểm tra số lượng trong kho
        if ($newquantity > $item->Quantity)
            return response()->json(['message' => 'Không còn đủ số lượng sản phẩm']);
        $cart->total_amount = $cart->total_amount - $cartdt->quantity + $newquantity;
        $cart->total_price = $cart->total_price - $cartdt->price + $item->Price * $newquantity;
        $cartdt->quantity = $newquantity;
        $cartdt->price = $item->Price * $newquantity;
        $cart->save();
        $cartdt->save();
        return ApiResponse::ok($cart->load('cartDetails'));
    }

    public function deleteCartItem($cartdetailsid)
    {
        $cart = CartController::checkCart()->load('cartDetails');
        $cartdt = $cart->cartDetails->find($cartdetailsid);
        $cart->total_amount -= $cartdt->quantity;
        $cart->total_price -= $cartdt->price;
        $cartdt->delete();
        $cart->save();
        return CartController::getCarts();
    }

    public function deleteCartItemList(Request $request)
    {
        $cart = CartController::checkCart()->load('cartDetails');
        $cartDetailsIds = $request->input('cartDetailsIds');
        // Tìm các cartdetails cần xóa
        $cartDetails = CartDetail::whereIn('id', $cartDetailsIds)->get();
        foreach ($cartDetails as $cartdt) {
            if ($cartdt) {
                $cart->total_amount -= $cartdt->quantity;
                $cart->total_price -= $cartdt->price;
                $cartdt->delete();
                $cart->save();
            }
        }
        return CartController::getCarts();
    }
}
