<?php

namespace App\Http\Controllers\Api;


use App\Enums\Shipment;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /* *
    *
    * show all orders
    *
    * @param no
    * @return json formatted list of orders
    */
    public function index()
    {
        if (auth()->user()->role == "admin") {
            $ordersList = Order::whereNull('deleted_at')->get();
        } else {
            $userId = auth()->user()->id;
            $ordersList = Order::where('user_id', $userId)->whereNull('deleted_at')->get();
        }

        if ($ordersList->isEmpty()) {
            return ApiResponse::notfound();
        } else {
            return ApiResponse::ok($ordersList);
        }
    }

    // Lấy thông tin chi tiết một đơn hàng
    // input: Id của đơn hàng muốn xem
    // output: 
    //    + Admin: Lấy được thông tin chi tiết của tất cả các đơn hàng chưa xoá
    //    + UseR: Chỉ lấy được thông tin chi tiết của đơn hàng chưa xoá của user
    public function show($id)
    {
        // Admin thì lấy được thông tin chi tiết tất cả các đơn hàng
        if (auth()->user()->role == "admin") {
            $order = Order::where('id', $id)->whereNull('deleted_at')->first();
        } else {

            // Lấy user_id của đơn hàng đang muốn lấy thông tin hoá đơn
            $userId = auth()->user()->id;
            $order = Order::where('id', $id)->whereNull('deleted_at')->first();
            $orderUserId = json_decode($order, false)->user_id;

            if ($orderUserId == $userId) {
                return ApiResponse::ok($order);
            } else {
                return ApiResponse::notfound();
            }
        }
        if ($order) {
            return ApiResponse::ok($order);
        } else return ApiResponse::notfound();
    }

    // Cập nhật thông tin một đơn hàng
    // input: request và id của đơn hàng muốn chỉnh sửa
    // output: nếu như các trường thông tin đáp ứng yêu cầu thì đơn hàng được chỉnh sửa
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validate = Validator::make($input, [
            'address' => ['nullable', 'string', 'max:255'],
            'shipment' => ['nullable', 'string', new Enum(Shipment::class)],
            'status' => ['nullable', 'string']
        ], $message = [
            'address' => [
                ':attribute must be string',
                ':attribute\'s max length is :max.'
            ],
            'shipment' => 'The :attribute is invalid',
            'status' => 'The :attribute must be string'
        ]);
        // Những thuộc tính được quyền chỉnh sửa: address, shipment, status
        if ($validate->fails()) {
            return $validate->errors();
        }

        // Lấy đơn hàng muốn update
        $updated = Order::where('id', $id)->whereNull('deleted_at')->first();


        if ($updated) {
            // Nếu như đơn hàng đã được giao thành công thì
            // cập nhật tình trạng thanh toán là đã thanh toán
            if ($input["shipment"] == "Thành công") {
                $input["status"] = "Đã thanh toán";
            }

            $updated->fill($input);
            $updated->save();
            return ApiResponse::ok($updated);
        } else {
            return ApiResponse::badrequest("Giá trị cập nhật không thoả");
        }
    }

    // Xoá đơn hàng
    // input: id đơn hàng muốn xoá
    // output: đơn hàng được xoá mềm
    public function delete($id)
    {
        $deleted = Order::where('id', $id)->first();
        if ($deleted == null)  return ApiResponse::notfound();
        else {
            $deleted->delete();
            return response()->json("Delete successfully", 200);
        }
    }

    public function search($id)
    {
        // Nếu như ô search có giá trị 
        // input: giá trị hiện được nhập tại search
        // output: danh sách những đơn hàng có id trùng với giá trị được nhập tại search
        // $request->filled('search')

        if (auth()->user()->role == "admin") {
            $orders = Order::where('id', $id)->whereNull('deleted_at')->get();
            if ($orders->isEmpty()) {
                return ApiResponse::notfound();
            } else {
                return ApiResponse::ok($orders);
            }
        } else if (auth()->user()->role == "user") {
            $orders = Order::where('user_id', auth()->user()->id)->where('id', $id)->whereNull('deleted_at')->get();
            if ($orders->isEmpty()) {
                return ApiResponse::notfound();
            } else {
                return ApiResponse::ok($orders);
            }
        }
    }

    // Phân trang
    // input: không có
    // output: 5 đơn hàng trên một trang
    //    + admin: lấy tất cả các đơn hàng chưa xoá
    //    + user: lấy tất cả các đơn hàng của user đó chưa xoá
    public function paginationPage()
    {

        if (auth()->user()->role == "admin") {
            $list = Order::whereNull('deleted_at')->paginate(5);
        } else {
            $list = Order::where('user_id', auth()->user()->id)->whereNull('deleted_at')->paginate(5);
        }

        if ($list->isEmpty()) return ApiResponse::notfound();
        else {
            return ApiResponse::ok($list);
        }
    }

    // Lấy tổng tiền tất cả các đơn hàng
    // input: không
    // output:
    //    + admin: tổng tất cả các đơn hàng chưa xoá và chưa huỷ
    //    + user: tổng tất cả các đơn hàng của user chưa xoá và chưa huỷ
    public function getTotal()
    {
        $total = 0;
        if (auth()->user()->role == "admin") {
            $orders = Order::where('shipment', 'not like', 'Huỷ')->whereNull('deleted_at')->get();
            foreach ($orders as $order) {
                $total += json_decode($order)->total;
            }
        } else {
            $orders = Order::where('shipment', 'not like', 'Huỷ')->where('user_id', auth()->user()->id)->get();
            foreach ($orders as $order) {
                $total += json_decode($order)->total;
            }
        }
        return ApiResponse::ok($total);
    }
}
