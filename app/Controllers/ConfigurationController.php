<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;

class ConfigurationController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        return $this->c->view->render($response, 'configuration.twig', [
            'title' => $this->c->lang->label()['configuration_label'],
        ]);
    }

}