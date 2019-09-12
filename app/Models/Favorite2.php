<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Favorite2 extends Model
{
    protected $table = 'vw_favorite2';
    protected $fillable = [ 
        'id',
        'customer_number',
        'article_number'
    ];
}