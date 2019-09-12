<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OfficialInvoices extends Model
{
    protected $table = 'vw_official_invoices';
    protected $fillable = [ 
        'id',
        'date',
        'customer_number',
        'order_amount',
        'reference',
        'representative',
        'send',
        'send_date',
    ];
}