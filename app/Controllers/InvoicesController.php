<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Dealer;
class InvoicesController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
    	$user = Dealer::find($_SESSION['id']);
    	$invoices = Invoice::where('customer_number', $user->customer_number)->get();

        return $this->c->view->render($response, 'invoices.twig', [
            'title' => $this->c->lang->label()['invoices'],
            'data' => $invoices,
        ]);
    }
}