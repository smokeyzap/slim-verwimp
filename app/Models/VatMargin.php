<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class VatMargin extends Model
{
    protected $table = 'vw_vat_margins';
    protected $fillable = [ 
        'id',
        'customer_number',
        'vat_margin',
    ];
}