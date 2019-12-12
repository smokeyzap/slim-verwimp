<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Article;
use App\Models\Artprop;
use App\Models\Order;
use App\Models\Favorite;
use App\Models\Image;
use App\Models\Stocks;
use Illuminate\Database\Capsule\Manager as DB;

class ItemIndexController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $bicycles = Article::select(DB::raw("group_concat(distinct(group_name)) as group_name, group_concat(distinct(sub_group)) as sub_group, group_concat(distinct(title)) as title"))
            ->where('categorie', 'FTS')
            ->groupBy('sub_group', 'group_name')
            ->get()
            ->groupBy('group_name')
            ->toArray();

        $bike_parts = Article::select(DB::raw("group_concat(distinct(group_name)) as group_name, group_concat(distinct(sub_group)) as sub_group, group_concat(distinct(title)) as title"))
            ->where('categorie', 'FO')
            ->groupBy('sub_group', 'group_name')
            ->get()
            ->groupBy('group_name')
            ->toArray();

        $scooter_parts = Article::select(DB::raw("group_concat(distinct(group_name)) as group_name, group_concat(distinct(sub_group)) as sub_group, group_concat(distinct(title)) as title"))
            ->whereIn('categorie', ['FBO','BO'])
            ->groupBy('sub_group', 'group_name')
            ->get()
            ->groupBy('group_name')
            ->toArray();

        $artprops = Artprop::distinct()->get(['template'])->where('template', '!=', "");
        
        return $this->c->view->render($response, 'item_index.twig', [
            'title' => $this->c->lang->label()['item_index'],
            'bicycles' => $bicycles,
            'bike_parts' => $bike_parts,
            'scooter_parts' => $scooter_parts,
            'artprops' => $artprops
        ]);
    }

    public function getAllBikes (Request $request, Response $response) 
    {
        $bikes = Article::where('categorie', 'FTS')->get(['item_number', 'name', 'sales_price']);

    	$output = [];
    	foreach ($bikes as $article) {
    		$output[] = [
    			$article->item_number,
    			$article->name,
    			$article->sales_price,
    		];
    	}
    	return $response->withJson(["data"=>$output]);
    }

    public function getArticles (Request $request, Response $response) 
    {
        $postData = $request->getAttribute('data');
        echo $postData;
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

        $artprops = Artprop::where('search_number', $article->search_number)
                    ->orderBy('characteristic', 'asc')        
                    ->get(['characteristic','value']);

        if (!$order) {
            $qty = 0;
        } else {
            $qty = $order->quantity;
        }

        $stock = new Stocks;
        $output[] = [
            'favorite' => $fav,
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
            'your_price'=>number_format((float)round($article->current_purchase_price / 1.21, 2), 2, '.', ''),
            'description' => $article->book_info,
            'image'=>Image::where('line_number', $article->sort_number)->first()->image,
            'artprop' => $artprops,
        ];
    	return $response->withJson(["data"=>$output]);
    }
}