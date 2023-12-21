<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'discount_type' => 'required|string',
            'discount_value' => 'required|numeric',
            'start_date' => 'required|string',
            'end_date' => 'required|string',
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
            return response()->json(["status" => 201, "data" => $add_voucher]);
        } else {
            return response()->json(["status" => 500, "error" => "Có lỗi khi thêm"]);
        }
    }

    public function list()
    {
        $list = Voucher::withTrashed()->paginate();

      if ($list->isEmpty()) {
          return ApiResponse::notfound("Resource is empty");
      } else {
         
          return ApiResponse::ok($list->toArray());
      }
    }

    public function getVoucherByCode($code)
    {
        $voucher = Voucher::where('code', $code)->where('deleted_at', null)->first();
        if ($voucher) {
            if ($voucher->current_usage >= $voucher->max_usage) return response()->json(["error" => "Voucher đã hết!"]);
            $voucher->current_usage = $voucher->current_usage + 1;
            $voucher->save();
            // $dateToCompare = Carbon::createFromFormat('m d Y', $$voucher->end_date);

            // // Get the current date and time
            // $currentDateTime = Carbon::now();
            // if ($dateToCompare < $currentDateTime) return response()->json(["error" => "Voucher đã hết hạn!"]);
            return ApiResponse::ok($voucher);
        } else return ApiResponse::notfound("Product Item not found");
    }

    public function delete($rid)
    {
        $deleted = Voucher::where('voucher_id', $rid)->first();
        if (!$deleted)  return ApiResponse::notfound("cannot find voucher to delete");
        else {
            $deleted->delete();
            return response()->json("deleted successfully", 200);
        }
    }

    public function listVoucher()
    {
        $list = Voucher::whereNull('deleted_at')->get();
        if (!$list) return ApiResponse::notfound("Resource is empty");
        else {
            return ApiResponse::ok($list);
        }
    }
}
