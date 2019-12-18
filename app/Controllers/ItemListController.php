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

class ItemListController extends Controller
{
    public function index (Request $request, Response $response) 
    {       
    	$item_list = Article::orderBy('id', 'asc')->first();
    	$image = Image::where('line_number', $item_list->sort_number)->first();
        $favorite = Favorite::where('customer_number', $_SESSION['customer_number'])
                        ->where('article_number', $item_list->item_number)
                        ->first();
        $fav = ($favorite)?true:false;

        $stock = new Stocks;
        return $this->c->view->render($response, 'item_list.twig', [
            'title' => $this->c->lang->label()['item_list'],
            'item_list' => $item_list,
            'stock_status' => $stock->stockStatus($item_list->sort_number),
            'image' => $image,
            'favorite' => $fav,
        ]);
    }

    public function getArticles (Request $request, Response $response) 
    {
        $table = 'vw_articles';
 
        // Table's primary key
        $primaryKey = 'id';
         
        // Array of database columns which should be read and sent back to DataTables.
        // The `db` parameter represents the column name in the database, while the `dt`
        // parameter represents the DataTables column identifier. In this case simple
        // indexes
        $columns = array(
            array( 'db' => 'id', 'dt' => 0 ),
            array( 'db' => 'item_number',  'dt' => 1 ),
            array( 'db' => 'name',   'dt' => 2 ),
            array( 'db' => 'current_purchase_price',     'dt' => 3 ),
            array( 'db' => 'current_purchase_price' , 'dt' => 4),
            array( 'db' => 'sales_price', 'dt' => 5)
        );
         
        // SQL server connection information
        $sql_details = array(
            'user' => getenv('DB_USERNAME'),
            'pass' => getenv('DB_PASSWORD'),
            'db'   => getenv('DB_NAME'),
            'host' => getenv('DB_HOSTNAME')
        );
        
        /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP
         * server-side, there is no need to edit below this line.
         */
        return $response->withJson(
            $this->c->ssp->simple( $_GET, $sql_details, $table, $primaryKey, $columns )
        );
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
}