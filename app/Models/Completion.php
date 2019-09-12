<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Completion extends Model
{
    protected $table = 'vw_completion';
    protected $fillable = [ 
        'id',
        'customer_number',
        'completion_from',
        'completion_until',
        'completion_precision',
    ];
}