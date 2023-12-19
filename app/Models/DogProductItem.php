<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DogProductItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'ItemName',
        'Price',
        'Category',
        'Description',
        'Images',
        'Quantity',
        'IsInStock'
    ];
}
