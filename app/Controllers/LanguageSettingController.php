<?php
namespace App\Controllers;

use Psr\Http\Message\{
    ServerRequestInterface as Request, 
    ResponseInterface as Response };
use App\Controllers\Controller;

class LanguageSettingController extends Controller
{
    public function index (Request $request, Response $response) 
    {   
        return $this->c->view->render($response, 'language.twig', [
            'title' => $this->c->lang->label()['language_label'],
        ]);
    }

    public function postLanguage (Request $request, Response $response)
    {

    }
}