<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';
    protected $primaryKey = 'appointment_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'dog_item_id',
        'phone_number',
        'service',
        'date',
        'hour',
        'description',
        'status',
        'result',
        'is_cancel',
    ];

    protected $casts = [
        'is_cancel' => 'boolean',
    ];
}
