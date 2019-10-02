<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OrderOld extends Model
{
    protected $table = 'vw_orders_old';
    protected $fillable = [ 
        'id',
        'customer_number',
        'invoice_number',
        'date',
        'order_order',
        'sent',
        'reference',
        'notes',
        'order_amount',
        'representative',
        'representative_name',
        'ip_address',
        'order_time',
        'company',
        'first_name',
        'name',
        'address',
        'number',
        'zip',
        'city',
        'country',
        'email',
        'phone',
    ];
}

