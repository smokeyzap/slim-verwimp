<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'vw_invoices';
    protected $fillable = [ 
        'id',
        'customer_number',
        'invoice_date',
        'expiry_date',
        'total_amount',
        'open_amount',
        'type',
        'counter',
    ];
}