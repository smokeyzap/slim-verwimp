<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LoginCodes extends Model
{
    protected $table = 'vw_security';
    protected $fillable = [ 
        'customer_number', 
        'password', 'name', 
        'full_access', 
        'purchase_price_access', 
        'invoice_access', 
        'customer_info_access', 
        'question_access', 
        'offer_access', 
        'offer_order', 
        'offer_favorite', 
        'new_access', 
        'new_order', 
        'new_favorite', 
        'closeout_access', 
        'closeout_order', 
        'closeout_favorite', 
        'list_access', 
        'list_order', 
        'list_favorite', 
        'index_access', 
        'index_order', 
        'index_favorite', 
        'favorite_access', 
        'favorite_order', 
        'favorite_remove', 
        'order_access', 
        'order_send', 
        'order_addition'
    ];
}