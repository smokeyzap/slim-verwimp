<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Order;
use App\Models\Article;

class OrderListController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
    	$all_orders = Order::where('customer_number', $_SESSION['customer_number'])
    					->where('sent', 0)
    					->get();


    	$orders = [];

    	foreach ($all_orders as $order) {
    		$orders[] = [
    			'item_number' => $order->item_number,
    			'quantity' => $order->quantity,
    			'item_number' => $order->item_number,
    			'description' => Article::where('item_number', $order->item_number)->first()->name,
    			'retail_price' => Article::where('item_number', $order->item_number)->first()->current_purchase_price,
    		];
    	}

        return $this->c->view->render($response, 'order_list.twig', [
            'title' => $this->c->lang->label()['order_list'],
            'orders' => $orders,
        ]);
    }

    public function postAddToCart (Request $request, Response $response) 
    {

        $postData = $request->getParsedBody();
        $order = new Order;

        $order->addOrder($_SESSION['customer_number'], $postData['item_number'], $postData['quantity']);
    }

    public function removeFromCart (Request $request, Response $response)
    {
    	$id = $request->getAttribute('id');
		dump($id);die();
    	$order = Order::where('item_number', $id)
    				->where('customer_number', $_SESSION['customer_number'])
    				->where('sent', 0)
    				->first();
    	if (!$order) {
    		return $response->withRedirect($this->c->router->pathFor('get.order.list'));
    	} else {
    		$order->delete();
    		return $response->withRedirect($this->c->router->pathFor('get.order.list'));
    	}
	}
	
	public function removeFromOrder (Request $request, Response $response)
    {
    	$id = $request->getAttribute('item_number');

    	$order = Order::where('item_number', $id)
    				->where('customer_number', $_SESSION['customer_number'])
    				->where('sent', 0)
					->first();
					
		$order->delete();
		$this->c->flash->addMessage('info', 'Product has been removed from the order');
		return $response->withRedirect($this->c->router->pathFor('get.order.list'));
	}
	
	public function updateCartFromOrder (Request $request, Response $response)
	{
		
		$item_number = $request->getAttribute('item_number');
		$qty = $request->getAttribute('qty');
		$order = Order::where('item_number', $item_number)
				->where('customer_number', $_SESSION['customer_number'])
				->where('sent', 0)
				->first();

		$order->quantity = $qty;
		$order->save();

		$this->c->flash->addMessage('info', 'Quantity changed.');
		return $response->withRedirect($this->c->router->pathFor('get.order.list'));
	}
}