<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Article;
use App\Models\Image;
use App\Models\Order;


class OfferController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
    	$item_list = Article::orderBy('id', 'desc')->first();
    	$image = Image::where('line_number', $item_list->sort_number)->first();
        $favorite = Favorite::where('customer_number', $_SESSION['customer_number'])
                        ->where('article_number', $item_list->item_number)
                        ->first();

        $fav = ($favorite)?true:false;
        return $this->c->view->render($response, 'offer.twig', [
            'title' => $this->c->lang->label()['offer'],
            'item_list' => $item_list,
            'image' => $image,
            'favorite' => $fav,
        ]);
    }

    public function getArticles (Request $request, Response $response) 
    {
    	$articles = Article::where('id', '<', 10)->get();

    	$output = [];
    	foreach ($articles as $article) {
    		$output[] = [
    			$article->id,
    			$article->item_number,
    			$article->name,
    			$article->current_purchase_price,
    			number_format((float)round($article->sales_price / 1.21, 2), 2, '.', ''),
    			$article->sales_price,
    		];
    	}
    	return $response->withJson(["data"=>$output]);
    }

    public function getArticleDetails (Request $request, Response $response)
    {
    	$postData = $request->getParsedBody();
    	$articles = Article::where('item_number', $postData['item_number'])->get();


    	$output = [];
    	foreach ($articles as $article) {
            $order = Order::where('customer_number', $_SESSION['customer_number'])
                        ->where('item_number', $article->item_number)
                        ->where('sent', 0)
                        ->first();

            $favorite = Favorite::where('customer_number', $_SESSION['customer_number'])
                        ->where('article_number', $article->item_number)
                        ->first();

            $fav = ($favorite)?true:false;
                        
            if (!$order) {
                $qty = 0;
            } else {
                $qty = $order->quantity;
            }

    		$output[] = [
                'favorite' => $fav,
    			'id'=>$article->id,
                'quantity' => $qty,
    			'item_number'=>$article->item_number,
    			'barcode' => $article->barcode,
    			'item_name'=>$article->name,
   				'brand' => $article->brand,
   				'packaging' => $article->packaging,
    			'suggested_retail_price'=>$article->sales_price,
    			'purchase_price'=>$article->current_purchase_price,
    			'your_price'=>number_format((float)round($article->current_purchase_price / 1.21, 2), 2, '.', ''),
    			'description' => $article->book_info,
    			'image'=>Image::where('line_number', $article->sort_number)->first()->image,
    		];
    	}
    	return $response->withJson(["data"=>$output]);
    }

    public function postAddToCart (Request $request, Response $response) 
    {

        $postData = $request->getParsedBody();
        $order = new Order;

        $order->addOrder($_SESSION['customer_number'], $postData['item_number'], $postData['quantity']);
    }
}