<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    protected $table = 'vw_order_line';
    protected $fillable = [ 
        'id',
        'invoice_number',
        'article_number',
        'search_number',
        'name',
        'quantity',
        'purchase_price',
        'original_purchase_price',
        'agreement',
        'notes',
    ];
}