<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    protected $table = 'vw_wishlist';
    protected $fillable = [ 
        'customer_number',
        'item_number',
    ];
}