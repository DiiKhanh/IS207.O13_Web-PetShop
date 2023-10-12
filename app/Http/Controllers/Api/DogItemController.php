<?php

namespace App\Http\Controllers\Api;

use App\Models\DogItem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Responses\ApiResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use \Illuminate\Validation\ValidationException;

class DogItemController extends Controller
{
    public function list()
    {
        // Sử dụng Eloquent để lấy danh sách DogItem có trường 'deleted_at' là null
        $list = DogItem::whereNull('deleted_at')->get();

        if ($list->isEmpty()) {
            return ApiResponse::notfound("Resource is empty");
        } else {
            $list->transform(function ($item) {
                // Giải mã trường 'Images' từ JSON thành mảng
                $images = json_decode($item->Images);
                if (json_last_error() == JSON_ERROR_NONE) {
                    $item->Images = $images;
                } else {
                    $item->Images = [];
                }
                return $item;
            });
            return ApiResponse::ok($list->toArray());
        }
    }

    public function getDogById($id)
    {
        $product = DogItem::where('id', $id)->whereNull('deleted_at')->first();

        if ($product) {
            $product->Images = json_decode($product->Images);
            return ApiResponse::ok($product);
        } else {
            return ApiResponse::notfound("Dog Item not found");
        }
    }

    public function getDogByName($name)
    {
        $products = DogItem::where('DogName', 'LIKE', '%' . $name . '%')->whereNull('deleted_at')->get();

        if ($products->isEmpty()) {
            return ApiResponse::notfound("No Dog Items found");
        } else {
            $products->transform(function ($item) {
                $item->Images = json_decode($item->Images);
                return $item;
            });
            return ApiResponse::ok($products->toArray());
        }
    }

    //TODO: Hỏi thử các trường đã đúng chưa
    public function create(Request $request)
    {
        try {
            $request->validate([
                'DogName' => 'required|string',
                'DogSpecies' => 'required|numeric',
                'Price' => 'required|numeric',
                'Color' => 'required|string',
                'Sex' => 'required|string',
                'Age' => 'required|numeric',
                'Origin' => 'required|string',
                'HealthStatus' => 'required|string',
                'Description' => 'required|string',
                'Images' => 'required|array',
            ]);
            // Hiện thị lỗi
        } catch (ValidationException $e) {
            return ApiResponse::badrequest($e->errors());
        }

        $name = $request->input('DogName');
        $find = DogItem::where('DogName', $name)->first();

        if ($find == null) {
            $add_product = new DogItem();
            $imgjson = json_encode($request->input('Images'));

            $add_product->DogName = $name;
            $add_product->DogSpecies = $request->input('DogSpecies');
            $add_product->Price = $request->input('Price');
            $add_product->Color = $request->input('Color');
            $add_product->Sex = $request->input('Sex');
            $add_product->Age = $request->input('Age');
            $add_product->Origin = $request->input('Origin');
            $add_product->HealthStatus = $request->input('HealthStatus');
            $add_product->Description = $request->input('Description');
            $add_product->Images = $imgjson;

            // Add the IsInStock attribute true
            $add_product->IsInStock = true;

            // Save the new record
            $add_product->save();

            // Decode the Images attribute as an array
            $add_product->Images = json_decode($imgjson);

            return ApiResponse::created($add_product);
        } else {
            return ApiResponse::badrequest("Dog Item already exists");
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'DogName' => 'nullable|string',
                'DogSpecies' => 'nullable|numeric',
                'Price' => 'nullable|numeric',
                'Color' => 'nullable|string',
                'Sex' => 'nullable|string',
                'Age' => 'nullable|numeric',
                'Origin' => 'nullable|string',
                'HealthStatus' => 'nullable|string',
                'Description' => 'nullable|string',
                'Images' => 'nullable|array',
            ]);
        } catch (ValidationException $e) {
            return ApiResponse::badrequest($e->errors());
        }

        $updated = DogItem::where('id', $id)->whereNull('deleted_at')->first();
        //xem $updated có phải là null không trước khi cố gắng truy cập vào thuộc tính của nó. 
        if ($updated) {
            if ($request->has('Images')) {
                $updated->images = json_encode($request->input('Images'));
            }
            // Update the attributes of the item based on the values in $request.
            $updated->fill($request->input());
            $updated->save();
            // If update is successful
            $updated->images = json_decode($updated->images);
            return ApiResponse::ok($updated);
        } else {
            // Xử lí trường hợp truy xuất tới softDelete /Không tồn tại
            return ApiResponse::notfound("can not update Dog Item ");
        }
    }


    public function delete($id)
    {
        $deleted = DogItem::where('id', $id)->first();
        if ($deleted == null) {
            return ApiResponse::notfound("cannot find dog to delete");
        } else {
            $deleted->delete();
            return response()->json("deleted successfully", 200);
        }
    }
}
