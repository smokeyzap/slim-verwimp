<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReturnReceipt extends Model
{
    protected $table = 'vw_returns_receipt';
    protected $fillable = [ 
        'receipt_number',
        'customer_number',
        'date',
        'balie',
    ];
}