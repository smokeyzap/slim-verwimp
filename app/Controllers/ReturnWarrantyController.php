<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;

class ReturnWarrantyController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        return $this->c->view->render($response, 'return-warranty.twig', [
            'title' => $this->c->lang->label()['return_warranty_label'],
        ]);
    }
}