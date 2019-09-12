<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'vw_favorite';
    protected $fillable = [ 
        'id',
        'customer_number',
        'article_number'
    ];

    public function addToFavorite ($item_number) 
    {
    	$fav = Favorite::where('customer_number', $_SESSION['customer_number'])
    					->where('article_number', $item_number)
    					->first();

    	if (!$fav) {
    		Favorite::create([
    			'customer_number' => $_SESSION['customer_number'],
    			'article_number' => $item_number,
    		]);
    		die('added');
    	} else {
    		$fav->delete();
    		die('removed');
    	}
    }
}