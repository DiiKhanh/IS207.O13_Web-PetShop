<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DogProductItem;
use App\Http\Responses\ApiResponse;
use Illuminate\Pagination\LengthAwarePaginator;
class ItemController extends Controller
{
    public function list()
    {
        $list = DogProductItem::whereNull('deleted_at')->get();
        if (!$list) return ApiResponse::notfound("Resource is empty");
        else {
            $list->transform(function ($item) {
                $item->Images = json_decode($item->Images);
                return $item;
            });
            return ApiResponse::ok($list->toArray());
        }
    }

    public function paginationPage()
    {
        // $defaultDogItemCall = 2;
        $list = DogProductItem::whereNull('deleted_at')->paginate();
        if (!$list) return ApiResponse::notfound("Resource is empty");
        else {
            $list->transform(function ($item) {
                $item->Images = json_decode($item->Images);
                return $item;
            });
            return ApiResponse::ok($list->toArray());
        }
    }

    public function getProductbyId($rid)
    {
        $product = DogProductItem::where('id', $rid)->where('deleted_at', null)->first();
        if ($product){
        $product->Images = json_decode($product->Images);
        return ApiResponse::ok($product);}
        else return ApiResponse::notfound("Product Item not found");
    }

    public function getProductbyName($name)
    {
        //eager loading diff from lazy loading
        //only load product where the column name contain $name
        $find = DogProductItem::where('ItemName', $name)->where('deleted_at',null)->first();

        if ($find) {
            $find->Images= json_decode($find->Images);
            return ApiResponse::ok($find);
        }
        else return ApiResponse::notfound("Product Item have not already exist");
    }

    public function create(Request $request)
    {
        $request->validate([
            'ItemName' => 'required|string',
            'Price' => 'required|numeric',
            'Category' => 'required|string',
            'Description' => 'required|string',
            'Images' => 'required|array',
            'Quantity' => 'required|integer'
        ]);

        $name = $request->input('ItemName');
        $find = DogProductItem::where('ItemName', $name)->first();

        if (!$find) {
            $add_product = new DogProductItem();
            $imgjson = json_encode($request->input('Images'));

            $add_product->ItemName = $name;
            $add_product->Price = $request->input('Price');
            $add_product->Category = $request->input('Category');
            $add_product->Description = $request->input('Description');
            $add_product->Images = $imgjson;
            $add_product->Quantity = $request->input('Quantity');
            $add_product->IsInStock = true;
            $add_product->save();

            $add_product->Images = json_decode($imgjson);
            return ApiResponse::created($add_product);
        } else {
            return ApiResponse::badrequest("Product Item already exists");
        }
    }

    public function update(Request $request, $rid)
    {
        $request->validate([
            'ItemName' => 'nullable|string',
            'Price' => 'nullable|numeric',
            'Category' => 'nullable|string',
            'Description' => 'nullable|string',
            'Images' => 'nullable|array',
            'Quantity' => 'nullable|integer',
            'IsInStock' => 'nullable|boolean'
        ]);

        $updated = DogProductItem::where('id', $rid)->where('deleted_at', null)->first();
        if ($request->has('Images')) {
            $updated->images = json_encode($request->input('Images'));
        }
        $updated->fill($request->input());
        $updated->save();
        if ($updated) {
            $updated->images= $request->input('Images');
            return ApiResponse::ok($updated);
        }
        else return ApiResponse::notfound("can not update product item");
        
    }

    public function delete($rid)
    {
        $deleted = DogProductItem::where('id', $rid)->first();
        if (!$deleted)  return ApiResponse::notfound("cannot find product item to delete");
        else {
            $deleted->delete();
            return response()->json("deleted successfully",200);
        }
        
    }

    public function getDogByIdAdmin($id)
    {
        $product = DogProductItem::where('id', $id)->first();

        if ($product) {
            $product->Images = json_decode($product->Images);
            return ApiResponse::ok($product);
        } else {
            return ApiResponse::notfound("Dog Item not found");
        }
    }

    public function paginationPageAdmin()
    {
      $list = DogProductItem::withTrashed()->paginate();

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
    // $list = DogProductItem::whereNull('deleted_at')->get();
    // if (!$list) return ApiResponse::notfound("Resource is empty");
    // else {
    //     $list->transform(function ($item) {
    //         $item->Images = json_decode($item->Images);
    //         return $item;
    //     });
    //     return ApiResponse::ok($list->toArray());
    // }
    }
}
