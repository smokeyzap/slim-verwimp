<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;

class RequestDigitalBillingController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        return $this->c->view->render($response, 'request-digital-billing.twig', [
            'title' => $this->c->lang->label()['request_digital_billing'],
        ]);
    }
}