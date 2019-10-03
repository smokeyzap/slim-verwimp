<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Dealer;
use App\Models\OrderOld;
use App\Models\OrderLine;

class OrderHistoryController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $orders = OrderOld::where('customer_number', $_SESSION['customer_number'])
                ->orderBy('id', 'desc')
                ->get();
        
        return $this->c->view->render($response, 'order-history.twig', [
            'title' => $this->c->lang->label()['order_history_label'],
            'orders' => $orders,
        ]);
    }

    public function getOrderItems (Request $request, Response $response)
    {
        $id = $request->getAttribute('id');

        $order_items = OrderLine::where('invoice_number', $id)->get();
        $output = [];
    	foreach ($order_items as $item) {
    		$output[] = [
                $item->invoice_number,
                $item->article_number,
                $item->name,
                $item->quantity,
                $item->purchase_price,
                $item->notes,
                $item->original_purchase_price
    		];
    	}
    	return $response->withJson(["data"=>$output]);
    }
}