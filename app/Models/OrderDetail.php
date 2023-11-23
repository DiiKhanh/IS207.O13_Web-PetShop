<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'dog_product_item_id',' dog_item_id', 'quantity', 'price'];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function dogItems() {
        return $this->belongsTo(DogItem::class);
    }

    public function dogProducts() {
        return $this->hasMany(DogProductItem::class);
    }
}
