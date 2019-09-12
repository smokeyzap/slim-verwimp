<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Backorder;
use App\Models\Article;

class BackOrderController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
    	$backorders = Backorder::where('customer_number', $_SESSION['customer_number'])->get();
        
    	$output = [];

    	foreach ($backorders as $b) {
    		$output[] = [
    			'description' => Article::where('search_number', $b->item_number)->first()->name,
    			'stock' => 'Sufficient',
    			'item_number' => $b->item_number,
    			'quantity' => $b->quantity,
    			'order_number' => $b->order_number,
    			'date' => $b->order_date,
    			'delivery_date' => $b->delivery_date,
    		];
    	}
        return $this->c->view->render($response, 'back_order.twig', [
            'title' => $this->c->lang->label()['backorders'],
            'data' => $output,
        ]);
    }
}