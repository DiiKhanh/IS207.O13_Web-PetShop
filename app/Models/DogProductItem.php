<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DogProductItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'ItemName',
        'Price',
        'Category',
        'Description',
        'Images',
        'Quantity'
    ];
}
