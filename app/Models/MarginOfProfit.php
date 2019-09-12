<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MarginOfProfit extends Model
{
    protected $table = 'vw_margin_of_profit';
    protected $fillable = [ 
        'id',
        'customer_number',
        'profit_group',
        'profit_sub_group',
        'title',
        'margin',
    ];
}