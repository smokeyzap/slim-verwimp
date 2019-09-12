<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Artprop extends Model
{
    protected $table = 'vw_artprop';
    protected $fillable = [ 
        'id',
        'search_number',
        'characteristic',
        'value',
        'template',
        'order_number',
    ];
}