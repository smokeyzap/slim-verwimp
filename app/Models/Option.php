<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'vw_options';
    protected $fillable = [ 
        'id',
        'customer_number',
        'option_id',
        'int_value',
        'float_value',
        'string_value',
    ];
}