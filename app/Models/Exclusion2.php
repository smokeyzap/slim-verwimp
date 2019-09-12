<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Exclusion2 extends Model
{
    protected $table = 'vw_exclusion2';
    protected $fillable = [ 
        'id',
        'customer_number',
        'type',
        'exclution_group',
        'value'
    ];
}