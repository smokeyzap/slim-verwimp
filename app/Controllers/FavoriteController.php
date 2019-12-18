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
use App\Models\Stocks;
use App\Models\QuanDisc;

class FavoriteController extends Controller
{
    
    public function index (Request $request, Response $response) 
    {   
        //get all item from favorite table
        $item_list = "";
        $image = "";
        $favorite = Favorite::where('customer_number', $_SESSION['customer_number'])->first();
        if ($favorite) {
            $item_list = Article::where('item_number', $favorite->article_number)->first();
            $image = Image::where('line_number', $item_list->sort_number)->first();
        }
        
        $stock = new Stocks;
        return $this->c->view->render($response, 'favorite.twig', [
            'title' => $this->c->lang->label()['favorites'],
            'item_list' => $item_list,
            'stock_status' => $stock->stockStatus($item_list->sort_number),
            'image' => $image,
        ]);
    }

    public function getArticles (Request $request, Response $response) 
    {
        $favorites = Favorite::where('customer_number', $_SESSION['customer_number'])->get();
        $fav_item_numbers = [];

        foreach ($favorites as $fav) {
            $fav_item_numbers[] = $fav->article_number;
        }

        $articles = Article::whereIn('item_number', $fav_item_numbers)->get();
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
                '<a href="'.$this->c->router->pathFor('remove.from.favorite',['id'=>$article->item_number]).'" onclick="return confirm(\'Remove from favorite. Continue?\')"><span class=\'glyphicon glyphicon-trash\' aria-hidden=\'true\'></span></a>'
            ];
        }
        return $response->withJson(["data"=>$output]);
    }

    public function removeFromFavorite (Request $request, Response $response)
    {
        $id = $request->getAttribute('id');
        Favorite::where('article_number', $id)
                ->where('customer_number', $_SESSION['customer_number'])
                ->delete();
        
        return $response->withRedirect($this->c->router->pathFor('get.favorites'));
    }

    public function getArticleDetails (Request $request, Response $response)
    {
        $postData = $request->getParsedBody();
        $article = Article::where('item_number', $postData['item_number'])->first();


        $output = [];

        $stock = new Stocks;
        $order = Order::where('customer_number', $_SESSION['customer_number'])
                    ->where('item_number', $article->item_number)
                    ->where('sent', 0)
                    ->first();

        $qty = (!$order)? 0 : $order->quantity;
        $stock = new Stocks;
        $discount = new QuanDisc;
        $your_price = $discount->getDiscount($article->discount_group);
        $output[] = [
            'id'=>$article->id,
            'quantity' => $qty,
            'stock_status' => $stock->stockStatus($article->sort_number),
            'item_number'=>$article->item_number,
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

    public function addToFavorite (Request $request, Response $response)
    {
    	$postData = $request->getParsedBody();
    	$fav = new Favorite;
    	$fav->addToFavorite($postData['item_number']);
    }
}