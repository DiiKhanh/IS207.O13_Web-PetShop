<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    //Tên table
    protected $table = 'cart_details';
    //Các cột có thể gán giá trị theo mảng
    protected $fillable = ['cart_id', 'dog_product_item_id',' dog_item_id', 'quantity', 'price'];
    public $timestamps = false;

    //Khai báo mối quan hệ
    public function cart() {
        return $this->belongsTo(Cart::class);
    }

    public function dogItems() {
        return $this->hasMany(DogItem::class);
    }

    public function dogProducts() {
        return $this->hasMany(DogProductItem::class);
    }
}