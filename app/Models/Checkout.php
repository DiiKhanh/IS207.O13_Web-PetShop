<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkout extends Model
{
    use HasFactory, SoftDeletes;

    // Khai báo tên bảng
    protected $table = 'checkouts';

    // Khai báo khóa chính
    protected $primaryKey = 'id';

    // Khai báo các cột có thể gán giá trị theo mảng
    protected $fillable = [
        'user_id',
        'data',
        'address',
        'status',
        'total',
        'payment',
        'email',
        'phoneNumber',
        'name'
    ];
}
