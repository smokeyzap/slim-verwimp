<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Backorder extends Model
{
    protected $table = 'vw_backorders';
    protected $fillable = [ 
        'id',
        'customer_number',
        'item_number',
        'quantity',
        'order_number',
        'order_date',
        'delivery_date',
    ];
}