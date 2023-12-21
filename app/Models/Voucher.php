<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    // Khai báo tên bảng
    protected $table = 'vouchers';

    // Khai báo khóa chính
    protected $primaryKey = 'voucher_id';

    // Khai báo các cột có thể gán giá trị theo mảng
    protected $fillable = [
        'code', 'discount_type', 'discount_value', 'start_date', 'end_date',
        'max_usage', 'current_usage', 'deleted_at',
    ];
}
