<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class QuanDisc extends Model
{
    protected $table = 'vw_quandisc';
    protected $fillable = [ 
        'id',
        'article_number',
        'discount_group',
        'customer_number',
        'min_number',
        'date_to',
        'discount',
    ];
}