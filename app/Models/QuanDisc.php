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

    public function getDiscount ($discount_group) 
    {

        $discount = Quandisc::where('customer_number', $_SESSION['customer_number'])
                ->where('discount_group', $discount_group)
                ->first();
        
        if (!$discount) {
            return 0;
        }

        return $discount->discount;
    }
}