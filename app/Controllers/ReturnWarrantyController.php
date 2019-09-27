<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\ReturnBikkel;

class ReturnWarrantyController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $returns = ReturnBikkel::where('customer_number', $_SESSION['customer_number'])->get();
        
        return $this->c->view->render($response, 'return-warranty.twig', [
            'title' => $this->c->lang->label()['return_warranty_label'],
            'data' => $returns,
        ]);
    }
}