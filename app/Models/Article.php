<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'vw_articles';
    protected $fillable = [ 
        'id',
        'item_number',
        'search_number',
        'sort_number',
        'name',
        'barcode',
        'categorie',
        'group_name',
        'sub_group',
        'title',
        'country',
        'brand',
        'packaging',
        'offer',
        'new',
        'clearance',
        'reservation',
        'photo',
        'net',
        'regulation_discount',
        'bill_discount',
        'discount_group',
        'location',
        'price_code',
        'current_purchase_price',
        'purchase_price',
        'sales_price_ex',
        'sales_price',
        'search_field_1',
        'search_field_2',
        'search_field_3',
        'mini_stock',
        'book_info',
        'number',
        'print_name_1',
        'print_name_2',
        'notes',
        'photo_1',
        'deleted',
        'outdoor',
        'video',
        'cur_stock',
        'stock_msg',
    ];
}