<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReturnBikkelReceipt extends Model
{
    protected $table = 'vw_returns_bikkel_receipt';
    protected $fillable = [ 
        'id',
        'receipt_number',
        'customer_number',
        'date',
    ];
}