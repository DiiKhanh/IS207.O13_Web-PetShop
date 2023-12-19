<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Http\Responses\ApiResponse;
use App\Models\Checkout;
use App\Models\DogItem;
use App\Models\DogProductItem;
use Illuminate\Pagination\LengthAwarePaginator;
use \Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|numeric',
                'total' => 'required|numeric',
                'status' => 'required|string',
                'payment' => 'required|string',
                'name' => 'required|string',
                'address' => 'required|string',
                'data' => 'required|array',
                'email' => 'required|string|email|max:255',
                'phoneNumber' => 'required|min:6',
            ]);
            // Hiện thị lỗi
        } catch (ValidationException $e) {
            return ApiResponse::badrequest($e->errors());
        }
            $add_checkout = new Checkout();
            $datajson = json_encode($request->input('data'));

            $data = json_decode($datajson);
            foreach ($data as $item) {
                if ($item->type == 'animal'){
                    $dogItem = DogItem::find($item->id);
                    if ($dogItem->IsInStock == false) {
                        return response()->json([
                            'error' => 'Sản phẩm đã hết! Vui lòng kiểm tra lại',
                        ]); 
                    }
                    if ($dogItem) {
                        $dogItem->IsInStock = false;
                        $dogItem->save();
                    }
                }
                if ($item->type == 'product'){
                    $productItem = DogProductItem::find($item->id);
                    if ($productItem->IsInStock == false) {
                        return response()->json([
                            'error' => 'Sản phẩm đã hết! Vui lòng kiểm tra lại',
                        ]); 
                    }
                    if ($productItem) {
                        $productItem->Quantity = $item->stock - $item->Quantity;
                        if ($item->stock - $item->Quantity == 0) {
                            $productItem->IsInStock = false;
                        }
                        $productItem->save();
                    }
                }
            }

            $add_checkout->user_id = $request->input('user_id');
            $add_checkout->total = $request->input('total');
            $add_checkout->status = $request->input('status');
            $add_checkout->payment = $request->input('payment');
            $add_checkout->address = $request->input('address');
            $add_checkout->email = $request->input('email');
            $add_checkout->name = $request->input('name');
            $add_checkout->phoneNumber = $request->input('phoneNumber');
            $add_checkout->data = $datajson;

            // Save the new record
            $add_checkout->save();

            // Decode the Images attribute as an array
            $add_checkout->data = json_decode($datajson);

            return response()->json([
                'status' => 201
            ]);
    }

    public function getByUser($user_id)
    {
        $list = Checkout::where('user_id', $user_id)->get();
        if ($list) {
            return response()->json($list);
        } else {
            return response()->json(['error' => 'Có lỗi xảy ra']);
        }
    }

    public function getById($id)
    {
        $item = Checkout::where('id', $id)->first();
        if ($item) {
            return response()->json($item);
        } else {
            return response()->json(['error' => 'Có lỗi xảy ra']);
        }
    }

    public function checkoutVnp(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $request->validate([
            'total' => 'required|numeric',
            'data' => 'required|array'
        ]);

        $datajson = json_encode($request->input('data'));

        $data = json_decode($datajson);
        foreach ($data as $item) {
            if ($item->type == 'animal'){
                $dogItem = DogItem::find($item->id);
                if ($dogItem->IsInStock == false) {
                    return response()->json([
                        'error' => 'Sản phẩm đã hết! Vui lòng kiểm tra lại',
                    ]); 
                }
            }
            if ($item->type == 'product'){
                $productItem = DogProductItem::find($item->id);
                if ($productItem->IsInStock == false) {
                    return response()->json([
                        'error' => 'Sản phẩm đã hết! Vui lòng kiểm tra lại',
                    ]); 
                }
            }
        }

        $vnp_TmnCode = "M5R49T9E"; //Mã định danh merchant kết nối (Terminal Id)
        $vnp_HashSecret = "ZRALTFWOZFBBRWZQXPXOTZSLFKBXXTQO"; //Secret key
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:5173/checkout/success";
        $vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
        $apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";
        $startTime = date("YmdHis");
        $expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));
        $vnp_TxnRef = rand(1,10000); //Mã giao dịch thanh toán tham chiếu của merchant
        $vnp_Amount = $request->input('total'); // Số tiền thanh toán
        $vnp_Locale = "vn"; //Ngôn ngữ chuyển hướng thanh toán
        // $vnp_BankCode = "NCB";
        $vnp_IpAddr = $request->ip(); //IP Khách hàng thanh toán
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount* 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => "Thanh toan GD:" . $vnp_TxnRef,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate"=>$expire
        ); 

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        return response()->json(["data" => $vnp_Url]);
    }

    public function getAll()
    {
        $list = Checkout::withTrashed()->paginate();

      if ($list->isEmpty()) {
          return ApiResponse::notfound("Không tìm thấy");
      } else {
          $list->transform(function ($item) {
              // Giải mã trường 'Images' từ JSON thành mảng
              $data = json_decode($item->data);
              if (json_last_error() == JSON_ERROR_NONE) {
                  $item->data = $data;
              } else {
                  $item->data = [];
              }
              return $item;
          });
          return ApiResponse::ok($list->toArray());
      }
    }

    public function update(Request $request, $rid)
    {
        $request->validate([
                'status' => 'required|string',
                'payment' => 'required|string',
                'name' => 'required|string',
                'address' => 'required|string',
                'email' => 'required|string|email|max:255',
                'phoneNumber' => 'required|min:6',
        ]);

        $updated = Checkout::where('id', $rid)->first();
        $updated->fill($request->input());
        $updated->save();
        if ($updated) {
            return response()->json(["status" => 200, "data" => $updated]);
        }
        else return ApiResponse::notfound("can not update product item");
        
    }
}
