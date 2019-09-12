<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OpenHour extends Model
{
    protected $table = 'vw_open_hours';
    protected $fillable = [ 
        'id',
        'customer_number',
        'open_day',
        'open_from',
        'open_until',
        'closed',
    ];
}