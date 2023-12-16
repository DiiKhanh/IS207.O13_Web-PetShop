<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Cart;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = JWTAuth::user();

        // 

        return ApiResponse::ok([
            'data' => $user,
            'token' => $token
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phoneNumber' => 'required|min:6',
            'firstName' => 'required|string|min:1',
            'lastName' => 'required|string|min:1',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phoneNumber' => $request->phoneNumber,
            'firstName' => $request->firstName,
            'lastName' => $request->lastName
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function userProfile()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
        ]);
    }

    public function userAdmin()
    {
        return response()->json([
            'status' => 'success admin',
            'user' => Auth::user(),
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'token' => Auth::refresh(),
        ]);
    }

    public function addCarts(Request $request)
    {
        $cart = new Cart();
        $cart->user_id = auth()->id();
        $cart->cart_data = '{"key2": "value2"}'; // Cung cấp dữ liệu giỏ hàng
        $cart->save();
        // Thêm sản phẩm vào giỏ hàng
        // $cart = Cart::create([
        //     'cart_data' => "sản phẩm 1",
        // ]);

        return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng', $cart]);
    }

    public function getCarts(Request $request)
    {
        $user = User::with('carts')->find(auth()->id());

        // Kiểm tra xem người dùng có tồn tại không
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }

        // Lấy danh sách giỏ hàng của người dùng
        $carts = $user->carts;

        return response()->json(['carts' => $carts]);
    }

    public function changePassword(Request $request)
    {
        # Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
        ]);
        #Match The Old Password
        if(!Hash::check($request->old_password, Auth::user()->password)){
            return response()->json(['error' => "Mật khẩu cũ không chính xác"]);
        }


        #Update the new Password
        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json(['status' => 200, 'data' => 'success']);
    }
}
