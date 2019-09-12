<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Exclusion extends Model
{
    protected $table = 'vw_exclusion';
    protected $fillable = [ 
        'id',
        'customer_number',
        'type',
        'exclution_group',
    ];
}