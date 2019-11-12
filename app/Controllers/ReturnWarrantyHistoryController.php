<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\ReturnBikkelLine;
use App\Models\ReturnBikkelReceipt;

class ReturnWarrantyHistoryController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $returns_history = ReturnBikkelReceipt::where('customer_number', $_SESSION['customer_number'])->get();
        return $this->c->view->render($response, 'return-warranty-history.twig', [
            'title' => $this->c->lang->label()['return_warranty_history'],
            'returns_history' => $returns_history,
        ]);
    }

    public function getReturnWarrantyHistoryItems (Request $request, Response $response)
    {
        $id = $request->getAttribute('id');

        $order_items = ReturnBikkelLine::where('receipt_number', $id)->get();
        $output = [];
    	foreach ($order_items as $item) {
            $registered = ($item == '1')?'Yes':'No';
    		$output[] = [
                $item->name,
                $item->frame_number,
                $item->battery_number,
                $item->date_of_purchase,
                $registered,
                $item->defective,
                $item->defect_code
    		];
    	}
    	return $response->withJson(["data"=>$output]);
    }
}