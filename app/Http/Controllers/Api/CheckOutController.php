<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\DogItem;
use App\Models\DogProductItem;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class CheckOutController extends Controller
{
    function checkOut(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'phone_number' => 'required|string|max:11|starts_with:0'
        ]);
        $cart = Cart::where('user_id', auth()->id())->first()->load('cartDetails');
        $order = new Order();
        $order->user_id = $cart->user_id;
        $order->total_price = $cart->total_price;
        $order->address = $request->input('address');
        $order->phone_number = $request->input('phone_number');
        $order->save();
        foreach ($cart->cartDetails as $cartdt) {
            $orderdt = new OrderDetail();
            $orderdt->order_id = $order->id;
            if ($cartdt->dog_product_item_id) {
                $product = DogProductItem::find($cartdt->dog_product_item_id);
                if ($product)
                    if ($product->Quantity < $cartdt->quantity) {
                        $order->forceDelete();
                        return ApiResponse::error('Không còn đủ số lượng hàng');
                    }
                $orderdt->dog_product_item_id = $cartdt->dog_product_item_id;
                $orderdt->quantity = $cartdt->quantity;
                $orderdt->price = $cartdt->price;
                $orderdt->save();
                $product->Quantity -= $orderdt->quantity;
                $product->save();
            }
            if ($cartdt->dog_item_id) {
                $product = DogItem::find($cartdt->dog_item_id);
                if (!$product) {
                    $order->forceDelete();
                    return ApiResponse::error('Không còn đủ số lượng hàng');
                }
                $orderdt->dog_item_id = $cartdt->dog_item_id;
                $orderdt->quantity = $cartdt->quantity;
                $orderdt->price = $cartdt->price;
                $orderdt->save();
                $product->IsInStock = 0;
                $product->save();
                $product->delete();
            }
        }
        CartDetail::where('cart_id', $cart->id)->delete();
        $cart->total_amount = 0;
        $cart->total_price = 0;
        $cart->save();
        return ApiResponse::ok($order->load('orderDetails'));
    }
}
