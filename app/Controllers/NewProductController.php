<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Article;
use App\Models\Image;
use App\Models\Order;
use App\Models\Favorite;
use App\Models\Stocks;
use App\Models\QuanDisc;

class NewProductController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $item_list = Article::orderBy('id', 'asc')
                    ->where('new', 1)
                    ->first();
    	$image = Image::where('line_number', $item_list->sort_number)->first();
        $favorite = Favorite::where('customer_number', $_SESSION['customer_number'])
                        ->where('article_number', $item_list->item_number)
                        ->first();
        $fav = ($favorite)?true:false;

        $stock = new Stocks;
        return $this->c->view->render($response, 'new_product.twig', [
            'title' => $this->c->lang->label()['new'],
            'item_list' => $item_list,
            'stock_status' => $stock->stockStatus($item_list->sort_number),
            'image' => $image,
            'favorite' => $fav,
        ]);
    }

    public function getArticles (Request $request, Response $response) 
    {
        $articles = Article::where('new', 1)->get();
        $discount = new QuanDisc;

    	$output = [];
    	foreach ($articles as $article) {
            $your_price = $discount->getDiscount($article->discount_group);
    		$output[] = [
    			$article->id,
    			$article->item_number,
    			$article->name,
    			($your_price == 0)?$article->current_purchase_price:$article->current_purchase_price - $your_price, 
                $article->current_purchase_price,
                $article->sales_price,
    		];
    	}
    	return $response->withJson(["data"=>$output]);
    }

    public function getArticleDetails (Request $request, Response $response)
    {
    	$postData = $request->getParsedBody();
    	$article = Article::where('item_number', $postData['item_number'])->first();

        $output = [];
        
        $order = Order::where('customer_number', $_SESSION['customer_number'])
                    ->where('item_number', $article->item_number)
                    ->where('sent', 0)
                    ->first();
        $favorite = Favorite::where('customer_number', $_SESSION['customer_number'])
                    ->where('article_number', $article->item_number)
                    ->first();

        $fav = ($favorite)?true:false;
        $qty = (!$order)? 0 : $order->quantity;
        $stock = new Stocks;
        $discount = new QuanDisc;
        $your_price = $discount->getDiscount($article->discount_group);
        $output[] = [
            'favorite' => $fav,
            'id'=>$article->id,
            'item_number'=>$article->item_number,
            'quantity' => $qty,
            'stock_status' => $stock->stockStatus($article->sort_number),
            'barcode' => $article->barcode,
            'item_name'=>$article->name,
            'brand' => $article->brand,
            'packaging' => $article->packaging,
            'suggested_retail_price'=>$article->sales_price,
            'purchase_price'=>$article->current_purchase_price,
            'your_price'=> ($your_price == 0)?$article->current_purchase_price:$article->current_purchase_price - $your_price, 
            'description' => $article->book_info,
            'image'=>Image::where('line_number', $article->sort_number)->first()->image,
        ];
    	return $response->withJson(["data"=>$output]);
    }

    public function postAddToCart (Request $request, Response $response) 
    {

        $postData = $request->getParsedBody();
        $order = new Order;

        $order->addOrder($_SESSION['customer_number'], $postData['item_number'], $postData['quantity']);
    }
}