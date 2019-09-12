<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Eticket extends Model
{
    protected $table = 'vw_eticket';
    protected $fillable = [ 
        'id',
        'customer_number',
        'name',
        'eticket_left',
        'eticket_right',
        'above',
        'below',
        'number_for_columns',
        'spacing',
        'height',
    ];
}