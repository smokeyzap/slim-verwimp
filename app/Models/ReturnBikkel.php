<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReturnBikkel extends Model
{
    protected $table = 'vw_returns_bikkel';
    protected $fillable = [ 
        'id',
        'customer_number',
        'item_number',
        'name',
        'bicycle_type',
        'frame_number',
        'accu_number',
        'date_of_purchase',
        'registered',
        'defective',
        'defect_code',
    ];
}