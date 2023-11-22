<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';

    protected $primary_key = 'id';

    protected $fillable = [
        'user_id',
        'address',
        'total',
        'shipment',
        'status',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function foreignKey()
    {
        // return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
