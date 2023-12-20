<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Http\Responses\ApiResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class VoucherController extends Controller
{
    public function list()
    {
        $list = Voucher::whereNull('deleted_at')->get();
        if (!$list) return ApiResponse::notfound("Resource is empty");
        else {
            return ApiResponse::ok($list);
        }
    }

    public function getVoucherByCode($code)
    {
        $voucher = Voucher::where('code', $code)->where('deleted_at', null)->first();
        if ($voucher) {
            return ApiResponse::ok($voucher);
        } else return ApiResponse::notfound("Product Item not found");
    }

    // public function searchVoucher(Request $request)
    // {
    // }
    public function create(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'discount_type' => 'required|in:percentage, fixed_amount',
            'discount_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'max_usage' => 'nullable|numeric'
        ]);

        $code = $request->input('code');
        $find = Voucher::where('code', $code)->first();

        if (!$find) {
            $add_voucher = new Voucher();

            $add_voucher->code = $code;
            $add_voucher->discount_type = $request->input('discount_type');
            $add_voucher->discount_value = $request->input('discount_value');
            $add_voucher->start_date = $request->input('start_date');
            $add_voucher->end_date = $request->input('end_date');
            $add_voucher->max_usage = $request->input('max_usage') ? $request->input('max_usage') : null;
            $add_voucher->save();
            return ApiResponse::created($add_voucher);
        } else {
            return ApiResponse::badrequest("Voucher have already existed");
        }
    }

    public function update(Request $request, $rid)
    {
        $request->validate([
            'code' => 'required|string',
            'discount_type' => 'required|in:percentage, fixed_amount',
            'discount_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'max_usage' => 'nullable|numeric',
            'current_usage' => 'nullable|numeric'
        ]);

        $updated = Voucher::where('id', $rid)->where('deleted_at', null)->first();
        // Kiểm tra nếu như số lần sử dụng của voucher chuẩn bị cập nhật
        // có lớn hơn số lần được sử dụng
        if (!is_null($request->input('max_usage'))) {
            if ($updated->current_usage > $request->input('max_usage')) {
                $updated->deleted_at = now();
            }
        }
        $updated->fill($request->input());
        $updated->save();
        if ($updated) {
            return ApiResponse::ok($updated);
        } else return ApiResponse::notfound("can not update voucher");
    }

    public function delete($rid)
    {
        $deleted = Voucher::where('id', $rid)->first();
        if (!$deleted)  return ApiResponse::notfound("cannot find voucher to delete");
        else {
            $deleted->delete();
            return response()->json("deleted successfully", 200);
        }
    }

    public function getVoucherByIdAdmin($id)
    {
        $voucher = Voucher::where('id', $id)->first();

        if ($voucher) {
            return ApiResponse::ok($voucher);
        } else {
            return ApiResponse::notfound("Voucher not found");
        }
    }

    public function paginationPageAdmin()
    {
        $list = Voucher::withTrashed()->paginate();

        if ($list->isEmpty()) {
            return ApiResponse::notfound("Resource is empty");
        } else {
            return ApiResponse::ok($list);
        }
    }
}
