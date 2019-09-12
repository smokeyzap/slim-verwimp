<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DropShipment extends Model
{
    protected $table = 'vw_drop_shipment';
    protected $fillable = [ 
        'id',
        'customer_number',
        'invoice_number',
        'date',
        'drop_shipment_order',
        'shipped',
        'reference',
        'notes',
        'order_amount',
        'representative',
        'representative_name',
        'ip_address',
        'time'
    ];
}