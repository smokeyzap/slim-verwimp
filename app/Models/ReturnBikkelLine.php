<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReturnBikkelLine extends Model
{
    protected $table = 'vw_returns_bikkel_line';
    protected $fillable = [ 
        'id',
        'receipt_number',
        'name',
        'bicycle_type',
        'frame_number',
        'battery_number',
        'date_of_purchase',
        'registered',
        'defective',
        'defect_code',
    ];
}