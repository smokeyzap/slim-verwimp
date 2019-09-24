<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Dealer;

class CustomerInformationController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        $dealers = Dealer::find($_SESSION['id']);
        return $this->c->view->render($response, 'customer-information.twig', [
            'title' => $this->c->lang->label()['customer_info'],
            'user_info' => $dealers,
        ]);
    }
}