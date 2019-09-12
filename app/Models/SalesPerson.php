<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SalesPerson extends Model
{
    protected $table = 'vw_sales_persons';
    protected $fillable = [ 
        'customer_number',
        'name',
        'address',
        'zip',
        'country',
        'city',
        'phone',
        'fax',
        'password',
    ];
}