<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Return extends Model
{
    protected $table = 'vw_returns';
    protected $fillable = [ 
        'id',
        'customer_number',
        'invoice_number',
        'quantity',
        'article_number',
        'search_number',
        'name',
        'reason',
        'location',
        'type',
        'problem_id',
        'issue',
        'part_number',
    ];
}