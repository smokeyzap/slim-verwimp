<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'vw_quantum';
    protected $fillable = [ 
        'id',
        'article_number',
        'quantum_nr',
        'purchase_price',
        'min_number',
        'date_number',
        'name',
        'type',
    ];
}