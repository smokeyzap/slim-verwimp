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

class FavoriteController extends Controller
{
    
    public function index (Request $request, Response $response) 
    {   
        //get all item from favorite table
        $favorite = Favorite::where('customer_number', $_SESSION['customer_number'])->first();
        $item_list = Article::where('item_number', $favorite->article_number)->first();
        $image = Image::where('line_number', $item_list->sort_number)->first();

        return $this->c->view->render($response, 'favorite.twig', [
            'title' => $this->c->lang->label()['favorites'],
            'item_list' => $item_list,
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
        $output = [];
        foreach ($articles as $article) {
            
            $output[] = [
                $article->id,
                $article->item_number,
                $article->name,
                $article->current_purchase_price,
                number_format((float)round($article->sales_price / 1.21, 2), 2, '.', ''),
                $article->sales_price,
                '<a href="'.$this->c->router->pathFor('remove.from.favorite',['id'=>$article->item_number]).'" onclick="return confirm(\'Remove from favorite. Continue?\')">remove</a>'
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
        $articles = Article::where('item_number', $postData['item_number'])->get();


        $output = [];
        foreach ($articles as $article) {
            $order = Order::where('customer_number', $_SESSION['customer_number'])
                        ->where('item_number', $article->item_number)
                        ->where('sent', 0)
                        ->first();
            if (!$order) {
                $qty = 0;
            } else {
                $qty = $order->quantity;
            }
            $output[] = [
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

    public function addToFavorite (Request $request, Response $response)
    {
    	$postData = $request->getParsedBody();
    	$fav = new Favorite;
    	$fav->addToFavorite($postData['item_number']);
    }
}