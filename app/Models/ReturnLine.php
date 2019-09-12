<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReturnLine extends Model
{
    protected $table = 'vw_returns_line';
    protected $fillable = [ 
        'id',
        'receipt_number',
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