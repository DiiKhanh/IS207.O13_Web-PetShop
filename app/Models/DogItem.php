<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DogItem extends Model
{
    use HasFactory, SoftDeletes;

    // Khai báo tên bảng
    protected $table = 'dog_items';

    // Khai báo khóa chính
    protected $primaryKey = 'id';

    // Khai báo các cột có thể gán giá trị theo mảng
    protected $fillable = [
        'DogName',
        'DogSpecies',
        'Price',
        'Color',
        'Sex',
        'Age',
        'Origin',
        'HealthStatus',
        'Description',
        'Images',
        'IsInStock'
    ];

    // TODO: Check lại thuộc tính có đúng không
    // Khai báo mối quan hệ với bảng dog_species_tbl
    public function dogSpecies()
    {
        return $this->belongsTo(DogSpecies::class, 'DogSpecies', 'id');
    }
}
