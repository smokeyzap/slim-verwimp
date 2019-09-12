<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class OfficialInvoiceLine extends Model
{
    protected $table = 'vw_official_invoicelines';
    protected $fillable = [ 
        'id',
        'receipt_number',
        'article_number',
        'search_number',
        'name',
        'quantity',
        'purchase_price',
        'original_purchase_price',
        'agreement',
        'notes',
    ];
}