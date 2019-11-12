<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\ReturnReceipt;
use App\Models\ReturnLine;

class ReturnWarrantyHistoryOtherController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $returns_history = ReturnReceipt::where('customer_number', $_SESSION['customer_number'])->get();
        return $this->c->view->render($response, 'return-warranty-other-history.twig', [
            'title' => $this->c->lang->label()['return_warranty_other_label'],
            'returns_other_history' => $returns_history,
        ]);
    }

    public function getReturnWarrantyOtherHistoryItems (Request $request, Response $response)
    {
        $id = $request->getAttribute('id');

        $order_items = ReturnLine::where('receipt_number', $id)->get();
        $output = [];
    	foreach ($order_items as $item) {
    		$output[] = [
                $item->article_number,
                $item->quantity,
                $item->name,
                $item->reason,
                $item->type,
                $item->part_number
    		];
    	}
    	return $response->withJson(["data"=>$output]);
    }
}