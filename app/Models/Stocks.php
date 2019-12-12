<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    protected $table = 'vw_stocks';
    protected $fillable = [ 
        'id',
        'sort_number',
        'stock',
    ];


    public function stockStatus($sortNumber)
    {
        $result = Stocks::where('sort_number', $sortNumber)->first();
        if (!$result) {
            return 'Out of stocks';
        }

        if ($result->stock <= 0) {
            return 'Out of stock';
        } elseif ($result->stock <= 5) {
            return 'Limited';
        } else {
            return 'Sufficient';
        }
    }
}