<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Order;
use App\Models\Article;
use App\Models\Dealer;
use App\Models\OrderOld;
use SimpleXMLElement;
use DOMDocument;


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

	public function sendOrders (Request $request, Response $response) 
	{

		$postData = $request->getParsedBody();
		
		$orders = Order::where('customer_number', $_SESSION['customer_number'])->get();
		$customer_info = Dealer::find($_SESSION['id']);

		$ip_address = $request->getAttribute('ip_address');
		$date = date('Y-m-d');

		$dom = new DOMDocument('1.0','UTF-8');
		$dom->formatOutput = true;

		$order = $dom->createElement('Order');
		$dom->appendChild($order);

		$header = $dom->createElement('Header');
		$order->appendChild($header);

		$items = $dom->createElement('Items');
		$order->appendChild($items);

		$header->appendChild( $dom->createElement('User', $customer_info->customer_number) );
		$header->appendChild( $dom->createElement('Naam', $customer_info->name) );
		$header->appendChild( $dom->createElement('Adres', $customer_info->address) );
		$header->appendChild( $dom->createElement('Gemeente', $customer_info->zip . ' ' . $customer_info->city) );
		$header->appendChild( $dom->createElement('Tel', $customer_info->phone) );
		$header->appendChild( $dom->createElement('Email', $customer_info->email) );
		$header->appendChild( $dom->createElement('Versie', '1.0.0') );
		$header->appendChild( $dom->createElement('Vertegenwoordiger', $customer_info->representative) );
		$header->appendChild( $dom->createElement('VertegenwoordigerNaam', $customer_info->representative) );
		$header->appendChild( $dom->createElement('Bonnummer', '') );
		$header->appendChild( $dom->createElement('Datum', $date) );
		$header->appendChild( $dom->createElement('Referentie', $postData['reference_number']) );
		$header->appendChild( $dom->createElement('Notities', '') );
		$header->appendChild( $dom->createElement('Orderbedrag', $postData['order_amount']) );

		foreach ($orders as $key => $order) {
			$item = $dom->createElement('Item');
			$items->appendChild($item);
			$article = Article::where('item_number', $order->item_number)->first();
			$item->appendChild( $dom->createElement('Artikelnummer', $article->item_number) );
			$item->appendChild( $dom->createElement('Zoeknummer', $article->search_number) );
			$item->appendChild( $dom->createElement('Naam', $article->name) );
			$item->appendChild( $dom->createElement('Aantal', $order->quantity) );
			$item->appendChild( $dom->createElement('Orig_Inkoopprijs', $article->current_purchase_price) );
			$item->appendChild( $dom->createElement('Inkoopprijs', $article->current_purchase_price) );
			$item->appendChild( $dom->createElement('Opmerking', '') );
			$item->appendChild( $dom->createElement('Afspraak', '0') );
			$item->appendChild( $dom->createElement('Locatie', $article->location) );
		}
		
		//Create custom file name
		$file_name = $customer_info->customer_number . '_' . date('ymd_His');
		$dom->save("order_orders/$file_name.xml") or die('XML Create Error');

		//Delete from orders temp
		foreach ($orders as $or) {
			$or->delete();
		}

		$o = OrderOld::create([
			'customer_number' => $customer_info->customer_number,
			'invoice_number' => '',
			'date' => $date,
			'order_order' => $dom->saveXML(),
			'sent' => 1,
			'reference' => $postData['reference_number'],
			'notes' => '',
			'order_amount' => $postData['order_amount'],
			'representative' => '',
			'representative_name' => '',
			'ip_address' => $ip_address,
			'company' => '',
			'first_name' => '',
			'name' => '',
			'address' => '',
			'number' => '',
			'zip' => '',
			'city' => '',
			'country' => '',
			'email' => '',
			'phone' => ''
		]);

		//email the owner
		

		$this->c->flash->addMessage('info', 'Order has been submitted successfully.');
		return $response->withRedirect($this->c->router->pathFor('get.order.list'));  
	}
}