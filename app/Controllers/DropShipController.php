<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Article;
use App\Models\Image;
use App\Models\DropShipOrderTemp;
use App\Models\Dealer;
use App\Models\OrderOld;
use App\Models\Stocks;
use App\Models\QuanDisc;
use DOMDocument;

class DropShipController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $all_orders = DropShipOrderTemp::where('customer_number', $_SESSION['customer_number'])
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
        
        $item_list = Article::orderBy('id', 'asc')->first();
    	$image = Image::where('line_number', $item_list->sort_number)->first();
        $stock = new Stocks;
        return $this->c->view->render($response, 'dropship.twig', [
            'title' => $this->c->lang->label()['dropship'],
			'item_list' => $item_list,
			'stock_status' => $stock->stockStatus($item_list->sort_number),
            'image' => $image,
            'orders' => $orders
        ]);
    }

    public function postAddToCart (Request $request, Response $response) 
    {

        $postData = $request->getParsedBody();
        $order = new DropShipOrderTemp;

        $order->addOrder($_SESSION['customer_number'], $postData['item_number'], $postData['quantity']);
    }

    public function getArticleDetails (Request $request, Response $response)
    {
    	$postData = $request->getParsedBody();
    	$article = Article::where('item_number', $postData['item_number'])->first();

		$output = [];
		
		$order = DropShipOrderTemp::where('customer_number', $_SESSION['customer_number'])
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

    public function sendOrders (Request $request, Response $response) 
	{

        $postData = $request->getParsedBody();
        
		
        $orders = DropShipOrderTemp::where('customer_number', $_SESSION['customer_number'])->get();
        
        if (count($orders) <= 0) {
            $this->c->flash->addMessage('error', 'Cart is empty.');
            return $response->withRedirect($this->c->router->pathFor('get.dropship'));
        }

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
		$dom->save("dropship_orders/$file_name.xml") or die('XML Create Error');

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
			'company' => $postData['dropship_company'],
			'first_name' => $postData['dropship_first_name'],
			'name' => $postData['dropship_name'],
			'address' => $postData['dropship_address'],
			'number' => $postData['dropship_number'],
			'zip' => $postData['dropship_postal_code'],
			'city' => $postData['dropship_city'],
			'country' => $postData['dropship_country'],
			'email' => $postData['dropship_email'],
			'phone' => $postData['dropship_tel']
		]);

		//email the owner
		

		$this->c->flash->addMessage('info', 'Order has been submitted successfully.');
		return $response->withRedirect($this->c->router->pathFor('get.dropship'));  
	}
}