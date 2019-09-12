<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'vw_purchase';
    protected $fillable = [ 
        'id',
        'customer_number',
        'item_number',
        'quantity',
        'notes',
        'order_purchase_price',
        'agreement',
    ];
}