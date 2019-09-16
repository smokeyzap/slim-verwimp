<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;

class TeamVerwimpController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        return $this->c->view->render($response, 'team-verwimp.twig', [
            'title' => $this->c->lang->label()['team_verwimp'],
        ]);
    }
}