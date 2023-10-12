<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DogSpecies extends Model
{
    use HasFactory, SoftDeletes;

    // Khai báo tên bảng
    protected $table = 'dog_species_tbl';

    // Khai báo khóa chính
    protected $primaryKey = 'DOG_SPECIES_ID';

    // Khai báo các cột có thể gán giá trị theo mảng
    protected $fillable = [
        'DOG_SPECIES_NAME',
        'IS_DELETED'
    ];
    // TODO: Check lại thuộc tính có đúng không
    // Khai báo mối quan hệ với bảng dog_item_tbl
    public function dogItems()
    {
        return $this->hasMany(DogItem::class, 'DOG_SPECIES', 'DOG_SPECIES_ID');
    }
}
