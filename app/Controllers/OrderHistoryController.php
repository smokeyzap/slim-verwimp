<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;
use App\Models\Dealer;

class OrderHistoryController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        return $this->c->view->render($response, 'order-history.twig', [
            'title' => $this->c->lang->label()['order_history_label'],
        ]);
    }
}